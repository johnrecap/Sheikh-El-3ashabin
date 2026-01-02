<?php

namespace Database\Seeders;

use App\Enums\GatewayMode;
use App\Enums\Activity;
use App\Enums\InputType;
use App\Models\GatewayOption;
use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;

class PaymentGatewayTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public array $gateways = [
        [
            "name"    => "Cash On Delivery",
            "slug"    => "cashondelivery",
            "misc"    => null,
            "status"  => Activity::ENABLE,
            "options" => [],
        ],
        [
            "name"    => "Credit",
            "slug"    => "credit",
            "misc"    => null,
            "status"  => Activity::ENABLE,
            "options" => [],
        ],
        [
            "name"    => "PayMob",
            "slug"    => "paymob",
            "misc"    => null,
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option"     => 'paymob_api_key',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'paymob_integration_id',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'paymob_iframe_id',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'paymob_hmac',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'paymob_mode',
                    "value"      => GatewayMode::SANDBOX,
                    "type"       => InputType::SELECT,
                    "activities" => [
                        GatewayMode::SANDBOX => "sandbox",
                        GatewayMode::LIVE    => "live",
                    ]
                ],
                [
                    "option"     => 'paymob_status',
                    "value"      => Activity::ENABLE,
                    "type"       => InputType::SELECT,
                    "activities" => [
                        Activity::ENABLE  => "enable",
                        Activity::DISABLE => "disable",
                    ]
                ],
            ]
        ]
    ];

    public function run(): void
    {
        // Delete all existing payment gateways and their options
        GatewayOption::where('model_type', PaymentGateway::class)->delete();
        PaymentGateway::truncate();

        foreach ($this->gateways as $gateway) {
            $payment = PaymentGateway::create([
                'name'   => $gateway['name'],
                'slug'   => $gateway['slug'],
                'misc'   => json_encode($gateway['misc']),
                'status' => $gateway['status']
            ]);

            if (file_exists(public_path('/images/seeder/payment-gateway/' . strtolower(str_replace(' ', '_', $gateway['slug'])) . '.png'))) {
                $payment->addMedia(public_path('/images/seeder/payment-gateway/' . strtolower(str_replace(' ', '_', $gateway['slug'])) . '.png'))->preservingOriginal()->toMediaCollection('payment-gateway');
            }
            $this->gatewayOption($payment->id, $gateway['options']);
        }
    }

    public function gatewayOption($id, $options): void
    {
        if (!blank($options)) {
            foreach ($options as $option) {
                GatewayOption::create([
                    'model_id'   => $id,
                    'model_type' => PaymentGateway::class,
                    'option'     => $option['option'],
                    'value'      => $option['value'] ?? "",
                    'type'       => $option['type'],
                    'activities' => json_encode($option['activities']),
                ]);
            }
        }
    }
}
