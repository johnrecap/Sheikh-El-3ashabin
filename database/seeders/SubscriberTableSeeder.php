<?php

namespace Database\Seeders;

use App\Models\Subscriber;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;


class SubscriberTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $envService = new EnvEditor();
        if ($envService->getValue('DEMO')) {
            Subscriber::insert([
                [
                    'email'      => 'subscriber@example.com',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'email'      => 'chayankhan@example.com',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'email'      => 'araki@example.com',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'email'      => 'adam@example.com',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'email'      => 'smith@example.com',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'email'      => 'john@example.com',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'email'      => 'doejack@example.com',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'email'      => 'jacksparow@example.com',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'email'      => 'daniecook@example.com',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'email'      => 'dipojonson@example.com',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
