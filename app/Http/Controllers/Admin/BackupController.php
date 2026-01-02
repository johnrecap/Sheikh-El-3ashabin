<?php

namespace App\Http\Controllers\Admin;

use App\Services\BackupService;
use Illuminate\Http\Request;

class BackupController extends AdminController
{
    protected $backupService;

    public function __construct(BackupService $backupService)
    {
        parent::__construct();
        $this->backupService = $backupService;
        $this->middleware(['permission:settings']);
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => $this->backupService->listBackups()
        ]);
    }

    public function store(): \Illuminate\Http\JsonResponse
    {
        $result = $this->backupService->createBackup();

        if ($result['success']) {
            return response()->json([
                'status' => true,
                'message' => __('backup.created_success'),
                'data' => $result
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => $result['message']
        ], 500);
    }

    public function restore(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'filename' => 'required|string'
        ]);

        $result = $this->backupService->restoreBackup($request->filename);

        if ($result['success']) {
            return response()->json([
                'status' => true,
                'message' => __('backup.restored_success')
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => $result['message']
        ], 500);
    }

    public function download(string $filename)
    {
        $path = $this->backupService->getBackupPath($filename);

        if ($path) {
            return response()->download($path);
        }

        abort(404);
    }

    public function destroy(string $filename): \Illuminate\Http\JsonResponse
    {
        if ($this->backupService->deleteBackup($filename)) {
            return response()->json([
                'status' => true,
                'message' => __('backup.deleted_success')
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => __('backup.delete_failed')
        ], 500);
    }
}
