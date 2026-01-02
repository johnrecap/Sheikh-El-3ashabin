<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\ProductCategory;
use App\Models\ProductBrand;
use App\Models\Unit;
use App\Models\Tax;
use App\Models\Product;
use App\Models\Stock;
use App\Models\ProductVariation;
use App\Models\User;
use App\Models\Address;
use App\Models\Order;
use App\Models\Slider;
use App\Models\Page;
use App\Models\Coupon;
use App\Models\PaymentGateway;
use App\Models\SmsGateway;

class BackupService
{
    protected string $backupPath;

    public function __construct()
    {
        $this->backupPath = database_path('backups');
        if (!File::exists($this->backupPath)) {
            File::makeDirectory($this->backupPath, 0755, true);
        }
    }

    /**
     * Create a full backup of production data
     */
    public function createBackup(int $retention = 10): array
    {
        try {
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $filename = "backup_{$timestamp}.json";

            $data = [
                'created_at' => Carbon::now()->toIso8601String(),
                'version' => '1.0',
                'tables' => [
                    'product_categories' => $this->backupProductCategories(),
                    'product_brands' => $this->backupProductBrands(),
                    'units' => $this->backupUnits(),
                    'taxes' => $this->backupTaxes(),
                    'products' => $this->backupProducts(),
                    'stocks' => $this->backupStocks(),
                    'product_variations' => $this->backupProductVariations(),
                    'users' => $this->backupUsers(),
                    'addresses' => $this->backupAddresses(),
                    'orders' => $this->backupOrders(),
                    'sliders' => $this->backupSliders(),
                    'pages' => $this->backupPages(),
                    'coupons' => $this->backupCoupons(),
                    'roles' => $this->backupRoles(),
                    'model_has_roles' => $this->backupModelHasRoles(),
                    'settings' => $this->backupSettings(),
                    'payment_gateways' => $this->backupPaymentGateways(),
                    'sms_gateways' => $this->backupSmsGateways(),
                    'media' => $this->backupMedia(),
                ]
            ];

            $filepath = $this->backupPath . DIRECTORY_SEPARATOR . $filename;
            File::put($filepath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            // Keep only last N backups
            $this->cleanOldBackups($retention);

            return [
                'success' => true,
                'filename' => $filename,
                'path' => $filepath,
                'size' => $this->formatBytes(filesize($filepath)),
                'tables_count' => count($data['tables']),
                'created_at' => $data['created_at']
            ];
        } catch (\Exception $e) {
            Log::error('Backup failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function restoreBackup(string $filename): array
    {
        try {
            $filepath = $this->backupPath . DIRECTORY_SEPARATOR . $filename;
            if (!File::exists($filepath)) {
                throw new \Exception("Backup file not found");
            }

            $content = File::get($filepath);
            $data = json_decode($content, true);

            DB::beginTransaction();

            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Truncate tables before restore
            $this->truncateTables();

            // Restore Data
            $this->restoreTable('product_categories', $data['tables']['product_categories'] ?? []);
            $this->restoreTable('product_brands', $data['tables']['product_brands'] ?? []);
            $this->restoreTable('units', $data['tables']['units'] ?? []);
            $this->restoreTable('taxes', $data['tables']['taxes'] ?? []);
            $this->restoreTable('products', $data['tables']['products'] ?? []);
            $this->restoreTable('stocks', $data['tables']['stocks'] ?? []);
            $this->restoreTable('product_variations', $data['tables']['product_variations'] ?? []);

            $this->restoreTable('users', $data['tables']['users'] ?? []);
            $this->restoreTable('roles', $data['tables']['roles'] ?? []);
            $this->restoreTable('model_has_roles', $data['tables']['model_has_roles'] ?? []);

            $this->restoreTable('addresses', $data['tables']['addresses'] ?? []);
            $this->restoreTable('orders', $data['tables']['orders'] ?? []);
            $this->restoreTable('sliders', $data['tables']['sliders'] ?? []);
            $this->restoreTable('pages', $data['tables']['pages'] ?? []);
            $this->restoreTable('coupons', $data['tables']['coupons'] ?? []);

            // Restore Settings and other configs
            $this->restoreTable('settings', $data['tables']['settings'] ?? []);
            $this->restoreTable('payment_gateways', $data['tables']['payment_gateways'] ?? []);
            $this->restoreTable('sms_gateways', $data['tables']['sms_gateways'] ?? []);

            $this->restoreTable('media', $data['tables']['media'] ?? []);

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            DB::commit();

            return ['success' => true];
        } catch (\Exception $e) {
            // Check if there's an active transaction before rolling back
            try {
                DB::rollBack();
            } catch (\Exception $rollbackException) {
                // Transaction might have been committed or never started
                Log::warning('Rollback skipped: ' . $rollbackException->getMessage());
            }

            // Make sure to re-enable foreign key checks
            try {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            } catch (\Exception $fkException) {
                Log::warning('Could not re-enable foreign key checks: ' . $fkException->getMessage());
            }

            Log::error('Restore failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function listBackups(): array
    {
        $files = File::files($this->backupPath);
        $backups = [];

        foreach ($files as $file) {
            if ($file->getExtension() === 'json') {
                $content = json_decode(File::get($file->getPathname()), true);
                $backups[] = [
                    'filename' => $file->getFilename(),
                    'size' => $this->formatBytes($file->getSize()),
                    'created_at' => $file->getMTime() * 1000,
                    'tables_count' => isset($content['tables']) ? count($content['tables']) : 0
                ];
            }
        }

        // Sort by newest first
        usort($backups, fn($a, $b) => $b['created_at'] - $a['created_at']);

        return $backups;
    }

    public function deleteBackup(string $filename): bool
    {
        $filepath = $this->backupPath . DIRECTORY_SEPARATOR . $filename;
        if (File::exists($filepath)) {
            return File::delete($filepath);
        }
        return false;
    }

    public function getBackupPath(string $filename): ?string
    {
        $filepath = $this->backupPath . DIRECTORY_SEPARATOR . $filename;
        return File::exists($filepath) ? $filepath : null;
    }

    // ===================== DATA FETCHERS =====================

    protected function backupProductCategories(): array
    {
        return DB::table('product_categories')->get()->map(fn($item) => (array)$item)->toArray();
    }
    protected function backupProductBrands(): array
    {
        return DB::table('product_brands')->get()->map(fn($item) => (array)$item)->toArray();
    }
    protected function backupUnits(): array
    {
        return DB::table('units')->get()->map(fn($item) => (array)$item)->toArray();
    }
    protected function backupTaxes(): array
    {
        return DB::table('taxes')->get()->map(fn($item) => (array)$item)->toArray();
    }
    protected function backupProducts(): array
    {
        return DB::table('products')->get()->map(fn($item) => (array)$item)->toArray();
    }
    protected function backupStocks(): array
    {
        return DB::table('stocks')->get()->map(fn($item) => (array)$item)->toArray();
    }
    protected function backupProductVariations(): array
    {
        return DB::table('product_variations')->get()->map(fn($item) => (array)$item)->toArray();
    }

    protected function backupUsers(): array
    {
        // Backup ALL users to ensure full system replication (Admins + Customers)
        return DB::table('users')->get()->map(fn($item) => (array)$item)->toArray();
    }

    protected function backupRoles(): array
    {
        return DB::table('roles')->get()->map(fn($item) => (array)$item)->toArray();
    }

    protected function backupModelHasRoles(): array
    {
        // This table maps users to roles
        return DB::table('model_has_roles')->get()->map(fn($item) => (array)$item)->toArray();
    }

    protected function backupAddresses(): array
    {
        return DB::table('addresses')->get()->map(fn($item) => (array)$item)->toArray();
    }

    protected function backupOrders(): array
    {
        return DB::table('orders')->get()->map(fn($item) => (array)$item)->toArray();
    }

    protected function backupSliders(): array
    {
        return DB::table('sliders')->get()->map(fn($item) => (array)$item)->toArray();
    }
    protected function backupPages(): array
    {
        return DB::table('pages')->get()->map(fn($item) => (array)$item)->toArray();
    }
    protected function backupCoupons(): array
    {
        return DB::table('coupons')->get()->map(fn($item) => (array)$item)->toArray();
    }

    protected function backupSettings(): array
    {
        return DB::table('settings')->get()->map(fn($item) => (array)$item)->toArray();
    }

    protected function backupPaymentGateways(): array
    {
        return DB::table('payment_gateways')->get()->map(fn($item) => (array)$item)->toArray();
    }
    protected function backupSmsGateways(): array
    {
        return DB::table('sms_gateways')->get()->map(fn($item) => (array)$item)->toArray();
    }

    protected function backupMedia(): array
    {
        return DB::table('media')->get()->map(function ($item) {
            return (array)$item;
        })->toArray();
    }

    // ===================== RESTORE HELPERS =====================

    protected function truncateTables(): void
    {
        // Use DELETE instead of TRUNCATE to maintain transaction integrity
        // TRUNCATE causes implicit commit in MySQL which breaks rollback capability

        $tables = [
            'model_has_roles',
            'roles',
            'addresses',
            'orders',
            'order_addresses',
            'order_outlet_addresses',
            'order_coupons',
            'stocks',
            'product_variations',
            'products',
            'product_categories',
            'product_brands',
            'units',
            'taxes',
            'sliders',
            'pages',
            'coupons',
            'settings',
            'payment_gateways',
            'sms_gateways',
            'media',
            'users',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->delete();
            }
        }
    }

    protected function restoreTable(string $tableName, array $rows): void
    {
        if (empty($rows)) return;

        // Special handling for addresses table (schema changed)
        if ($tableName === 'addresses') {
            $rows = $this->migrateAddressesData($rows);
        }

        // Chunk inserts to avoid placeholder limits
        $chunks = array_chunk($rows, 100);
        foreach ($chunks as $chunk) {
            DB::table($tableName)->insertOrIgnore($chunk);
        }
    }

    /**
     * Migrate old addresses format to new format
     * Old: address, apartment, latitude, longitude
     * New: governorate, city, street, building_number, full_address
     */
    protected function migrateAddressesData(array $rows): array
    {
        return array_map(function ($row) {
            // Map old columns to new columns
            $newRow = [
                'id' => $row['id'] ?? null,
                'user_id' => $row['user_id'] ?? null,
                'label' => $row['label'] ?? 'Home',
                'governorate' => 'غير محدد',
                'city' => 'غير محدد',
                'street' => $row['address'] ?? '',
                'building_number' => $row['apartment'] ?? '',
                'full_address' => ($row['address'] ?? '') . ' - ' . ($row['apartment'] ?? ''),
                'phone' => $row['phone'] ?? null,
                'creator_type' => $row['creator_type'] ?? null,
                'creator_id' => $row['creator_id'] ?? null,
                'editor_type' => $row['editor_type'] ?? null,
                'editor_id' => $row['editor_id'] ?? null,
                'created_at' => $row['created_at'] ?? now(),
                'updated_at' => $row['updated_at'] ?? now(),
            ];

            return $newRow;
        }, $rows);
    }

    // ===================== HELPERS =====================

    protected function cleanOldBackups(int $keep = 10): void
    {
        $files = File::files($this->backupPath);
        $jsonFiles = array_filter($files, fn($f) => $f->getExtension() === 'json');

        // Sort by modification time descending
        usort($jsonFiles, fn($a, $b) => $b->getMTime() - $a->getMTime());

        // Delete excess files
        if (count($jsonFiles) > $keep) {
            $filesToDelete = array_slice($jsonFiles, $keep);
            foreach ($filesToDelete as $file) {
                File::delete($file->getPathname());
            }
        }
    }

    protected function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
