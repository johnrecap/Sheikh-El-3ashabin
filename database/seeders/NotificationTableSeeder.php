<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\NotificationSetting;
use Dipokhalder\EnvEditor\EnvEditor;
use Smartisan\Settings\Facades\Settings;


class NotificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $envService = new EnvEditor();
        Settings::group('notification')->set([
            'notification_fcm_api_key'             => $envService->getValue('DEMO') ? 'AIzaSyAIWTpgWVVzqTDV1fdJcHNZGLVJLyK7g4E' : '',
            'notification_fcm_public_vapid_key'    => $envService->getValue('DEMO') ? 'BFALk2zVzuvcBA0evxfpfKgWjFSsef5yXyLtOOMOxENELv-r_VO-ABrtS3QoeatNnj4w5Oz7CxojQj3jmro1YUs' : '',
            'notification_fcm_auth_domain'         => $envService->getValue('DEMO') ? 'storeking-9a1da.firebaseapp.com' : '',
            'notification_fcm_project_id'          => $envService->getValue('DEMO') ? 'storeking-9a1da' : '',
            'notification_fcm_storage_bucket'      => $envService->getValue('DEMO') ? 'storeking-9a1da.firebasestorage.app' : '',
            'notification_fcm_messaging_sender_id' => $envService->getValue('DEMO') ? '116885060694' : '',
            'notification_fcm_app_id'              => $envService->getValue('DEMO') ? '1:116885060694:web:e9217095209ea5a1408ff7' : '',
            'notification_fcm_measurement_id'      => $envService->getValue('DEMO') ? 'G-0K9LVW114N' : '',
            'notification_fcm_json_file'           => '',
        ]);

        if ($envService->getValue('DEMO') && file_exists(public_path('/file/service-account-file.json'))) {
            $setting = NotificationSetting::where('key', 'notification_fcm_json_file')->first();
            $setting->addMedia(public_path('/file/service-account-file.json'))->preservingOriginal()->usingFileName('service-account-file.json')->toMediaCollection('notification-file');
        }
    }
}