<?php

namespace Database\Seeders;

use App\Enums\GatewayMode;
use App\Enums\Activity;
use App\Models\GatewayOption;
use App\Models\PaymentGateway;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;

class PaymentGatewayDataTableSeeder extends Seeder
{

    public array $gateways = [
        [
            "slug"    => "paypal",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'paypal_app_id',
                    "value"  => 'sb-qzxs18789565@business.example.com',
                ],
                [
                    "option" => 'paypal_client_id',
                    "value"  => 'AbcV-BG5b30hjofcp2dj41GB1OYXE8_9-egRlV8z4R7vHiA-1mgL3Fvj3pkrOJyq0dC_vHNRAh_tp74p'
                ],
                [
                    "option" => 'paypal_client_secret',
                    "value"  => 'REPLACE_ON_SERVER'
                ],
                [
                    "option" => 'paypal_mode',
                    "value"  => GatewayMode::SANDBOX
                ],
                [
                    "option" => 'paypal_status',
                    "value"  => Activity::ENABLE
                ],
            ]
        ],
        [
            "slug"    => "stripe",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'stripe_key',
                    "value"  => 'pk_test_6rhnZv1NmRtSp5DfziBO8YFb00X65CfFwq',
                ],
                [
                    "option" => 'stripe_secret',
                    "value"  => 'REPLACE_ON_SERVER',
                ],
                [
                    "option" => 'stripe_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'stripe_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "flutterwave",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'flutterwave_public_key',
                    "value"  => 'FLWPUBK_TEST-6902f8e874fe91b422045b09c02fff65-X',
                ],
                [
                    "option" => 'flutterwave_secret_key',
                    "value"  => 'REPLACE_ON_SERVER',
                ],
                [
                    "option" => 'flutterwave_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'flutterwave_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "paystack",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'paystack_public_key',
                    "value"  => 'pk_test_370ce5565f2a937efae6314df2dccba2781bfa69',
                ],
                [
                    "option" => 'paystack_secret_key',
                    "value"  => 'REPLACE_ON_SERVER',
                ],
                [
                    "option" => 'paystack_payment_url',
                    "value"  => 'https://api.paystack.co',
                ],
                [
                    "option" => 'paystack_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'paystack_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "sslcommerz",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'sslcommerz_store_name',
                    "value"  => 'testfoodkp1pi',
                ],
                [
                    "option" => 'sslcommerz_store_id',
                    "value"  => 'foodk6472ed754a400',
                ],
                [
                    "option" => 'sslcommerz_store_password',
                    "value"  => 'REPLACE_ON_SERVER',
                ],
                [
                    "option" => 'sslcommerz_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'sslcommerz_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "mollie",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'mollie_api_key',
                    "value"  => 'REPLACE_ON_SERVER',
                ],
                [
                    "option" => 'mollie_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'mollie_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "senangpay",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'senangpay_merchant_id',
                    "value"  => '108168665310430',
                ],
                [
                    "option" => 'senangpay_secret_key',
                    "value"  => 'REPLACE_ON_SERVER',
                ],
                [
                    "option" => 'senangpay_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'senangpay_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "bkash",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'bkash_app_key',
                    "value"  => '4f6o0cjiki2rfm34kfdadl1eqq',
                ],
                [
                    "option" => 'bkash_app_secret',
                    "value"  => 'REPLACE_ON_SERVER',
                ],
                [
                    "option" => 'bkash_username',
                    "value"  => 'sandboxTokenizedUser02'
                ],
                [
                    "option" => 'bkash_password',
                    "value"  => 'sandboxTokenizedUser02@12345'
                ],
                [
                    "option" => 'bkash_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'bkash_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "paytm",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'paytm_merchant_id',
                    "value"  => 'MhjqFc42556626519745',
                ],
                [
                    "option" => 'paytm_merchant_key',
                    "value"  => 'REPLACE_ON_SERVER',
                ],
                [
                    "option" => 'paytm_merchant_website',
                    "value"  => 'WEBSTAGING',
                ],
                [
                    "option" => 'paytm_channel',
                    "value"  => 'WEB',
                ],
                [
                    "option" => 'paytm_industry_type',
                    "value"  => 'Retail',
                ],
                [
                    "option" => 'paytm_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'paytm_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "razorpay",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'razorpay_key',
                    "value"  => 'rzp_test_eeBR6yhSmKHB65',
                ],
                [
                    "option" => 'razorpay_secret',
                    "value"  => 'REPLACE_ON_SERVER',
                ],
                [
                    "option" => 'razorpay_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'razorpay_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "mercadopago",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'mercadopago_client_id',
                    "value"  => '8223421224707412',
                ],
                [
                    "option" => 'mercadopago_client_secret',
                    "value"  => 'REPLACE_ON_SERVER',
                ],
                [
                    "option" => 'mercadopago_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'mercadopago_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "cashfree",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'cashfree_app_id',
                    "value"  => 'TEST408093487955435d9cfa4c9493390804',
                ],
                [
                    "option" => 'cashfree_secret_key',
                    "value"  => 'REPLACE_ON_SERVER',
                ],
                [
                    "option" => 'cashfree_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'cashfree_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "payfast",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'payfast_merchant_id',
                    "value"  => '10004002',
                ],
                [
                    "option" => 'payfast_merchant_key',
                    "value"  => 'q1cd2rdny4a53',
                ],
                [
                    "option" => 'payfast_passphrase',
                    "value"  => 'payfast',
                ],
                [
                    "option" => 'payfast_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'payfast_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "skrill",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'skrill_merchant_email',
                    "value"  => 'demoqco@sun-fish.com',
                ],
                [
                    "option" => 'skrill_merchant_api_password',
                    "value"  => 'REPLACE_ON_SERVER',
                ],
                [
                    "option" => 'skrill_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'skrill_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "phonepe",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'phonepe_client_id',
                    "value"  => 'PGTESTPAYUAT',
                ],
                [
                    "option" => 'phonepe_merchant_user_id',
                    "value"  => 'MUID123',
                ],
                [
                    "option" => 'phonepe_key_index',
                    "value"  => '1',
                ],
                [
                    "option" => 'phonepe_secret_key',
                    "value"  => 'REPLACE_ON_SERVER',
                ],
                [
                    "option" => 'phonepe_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'phonepe_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug" => "myfatoorah",
            "status" => Activity::ENABLE,
            "options" => [

                [
                    "option" => 'myfatoorah_api_key',
                    "value"  => 'REPLACE_ON_SERVER',
                ],
                [
                    "option" => 'myfatoorah_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'myfatoorah_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "easypaisa",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option"     => 'easypaisa_store_id',
                    "value"      => '641',
                ],
                [
                    "option"     => 'easypaisa_hash_key',
                    "value"      => "REPLACE_ON_SERVER",
                ],
                [
                    "option" => 'easypaisa_username',
                    "value"  => "pg-systems",

                ],
                [
                    "option" => 'easypaisa_password',
                    "value"  => "REPLACE_ON_SERVER",

                ],
                [
                    "option"     => 'easypaisa_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option"     => 'easypaisa_status',
                    "value"      => Activity::ENABLE,
                ],
            ]
        ],
        [
            "slug"    => "paymob",
            "status"  => Activity::ENABLE,
            "options" => [
                [
                    "option" => 'paymob_api_key',
                    "value"  => '',
                ],
                [
                    "option" => 'paymob_integration_id',
                    "value"  => '',
                ],
                [
                    "option" => 'paymob_iframe_id',
                    "value"  => '',
                ],
                [
                    "option" => 'paymob_hmac',
                    "value"  => '',
                ],
                [
                    "option" => 'paymob_mode',
                    "value"  => GatewayMode::SANDBOX,
                ],
                [
                    "option" => 'paymob_status',
                    "value"  => Activity::ENABLE,
                ],
            ]
        ]
    ];

    public function run(): void
    {
        $envService = new EnvEditor();
        if ($envService->getValue('DEMO')) {
            foreach ($this->gateways as $gateway) {
                $payment = PaymentGateway::where(['slug' => $gateway['slug']])->first();
                if ($payment) {
                    $payment->status = $gateway['status'];
                    $payment->save();
                }
                $this->gatewayOption($gateway['options']);
            }
        }
    }

    public function gatewayOption($options): void
    {
        if (!blank($options)) {
            foreach ($options as $option) {
                $gatewayOption = GatewayOption::where(['option' => $option['option']])->first();
                if ($gatewayOption) {
                    $gatewayOption->value = $option['value'];
                    $gatewayOption->save();
                }
            }
        }
    }
}
