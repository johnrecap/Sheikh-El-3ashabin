<?php

namespace App\Services;

use App\Http\Requests\NotificationRequest;
use App\Libraries\AppLibrary;
use App\Libraries\QueryExceptionLibrary;
use App\Models\NotificationSetting;
use Dipokhalder\EnvEditor\EnvEditor;
use Exception;
use Illuminate\Support\Facades\Log;
use Smartisan\Settings\Facades\Settings;

class NotificationService
{
    public $envService;

    public function __construct(EnvEditor $envEditor)
    {
        $this->envService = $envEditor;
    }

    /**
     * @throws Exception
     */
    public function list()
    {
        try {
            return Settings::group('notification')->all();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @param NotificationRequest $request
     * @return
     * @throws Exception
     */
    public function update(NotificationRequest $request)
    {
        try {
            AppLibrary::fcmDataBind($request);
            Settings::group('notification')->set($request->validated());

            if ($request->notification_fcm_json_file) {
                $newFilename = 'service-account-file' . '.' . $request->file('notification_fcm_json_file')->getClientOriginalExtension();
                $setting = NotificationSetting::where('key', 'notification_fcm_json_file')->first();
                $setting->clearMediaCollection('notification-file');
                $setting->addMedia($request->file('notification_fcm_json_file'))->usingFileName($newFilename)->toMediaCollection('notification-file');
            }
            return $this->list();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }
}
