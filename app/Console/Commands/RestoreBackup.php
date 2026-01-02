<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class RestoreBackup extends Command
{
    protected $signature = 'backup:restore {filename? : The backup filename to restore} {--force : Skip confirmation}';
    protected $description = 'Restore database from a backup file';

    public function handle(BackupService $backupService): int
    {
        $filename = $this->argument('filename');

        // If no filename provided, show list of available backups
        if (!$filename) {
            $backups = $backupService->listBackups();

            if (empty($backups)) {
                $this->error('No backup files found.');
                return 1;
            }

            $this->info('Available backups:');
            $this->table(['Filename', 'Size', 'Tables'], array_map(fn($b) => [
                $b['filename'],
                $b['size'],
                $b['tables_count']
            ], $backups));

            $filename = $this->ask('Enter the filename to restore');
        }

        // Confirmation (skip if --force)
        if (!$this->option('force')) {
            $this->warn('WARNING: This will DELETE all current data and restore from backup.');
            $answer = $this->ask('Type "confirm" to proceed');
            if (strtolower($answer) !== 'confirm') {
                $this->info('Restore cancelled.');
                return 0;
            }
        }

        $this->info('Starting restore from: ' . $filename);
        $this->newLine();

        $result = $backupService->restoreBackup($filename);

        if ($result['success']) {
            $this->info('Backup restored successfully!');
            return 0;
        } else {
            $this->error('Restore failed: ' . ($result['message'] ?? 'Unknown error'));
            return 1;
        }
    }
}
