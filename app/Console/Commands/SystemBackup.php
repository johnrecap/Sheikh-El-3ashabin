<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SystemBackup extends Command
{
    protected $signature = 'system:backup {--retention=20 : Number of backups to keep}';
    protected $description = 'Create a system backup and cleanup old backups';

    public function handle(BackupService $backupService): int
    {
        $this->info('Starting system backup...');

        try {
            $retention = (int) $this->option('retention');
            $result = $backupService->createBackup($retention);

            if ($result['success']) {
                $this->info("Backup created successfully: " . $result['filename']);
                Log::info('System backup created via scheduler', $result);
                return 0;
            } else {
                $this->error("Backup failed: " . ($result['message'] ?? 'Unknown error'));
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
}
