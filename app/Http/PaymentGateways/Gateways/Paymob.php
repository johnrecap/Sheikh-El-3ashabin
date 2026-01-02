<?php

namespace App\Http\PaymentGateways\Gateways;

use App\Enums\Activity;
use App\Models\CapturePaymentNotification;
use App\Models\Currency;
use App\Models\PaymentGateway;
use App\Services\PaymentAbstract;
use App\Services\PaymentService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Smartisan\Settings\Facades\Settings;

class Paymob extends PaymentAbstract
{
    public bool $response = false;
    public mixed $apiKey;
    public mixed $integrationId;
    public mixed $iframeId;
    public mixed $hmac;
    public bool $isSandbox;

    public function __construct()
    {
        $paymentService = new PaymentService();
        parent::__construct($paymentService);

        $this->paymentGateway = PaymentGateway::with('gatewayOptions')->where(['slug' => 'paymob'])->first();
        if (!blank($this->paymentGateway)) {
            $this->paymentGatewayOption = $this->paymentGateway->gatewayOptions->pluck('value', 'option');
            $this->apiKey = $this->paymentGatewayOption['paymob_api_key'] ?? '';
            $this->integrationId = $this->paymentGatewayOption['paymob_integration_id'] ?? '';
            $this->iframeId = $this->paymentGatewayOption['paymob_iframe_id'] ?? '';
            $this->hmac = $this->paymentGatewayOption['paymob_hmac'] ?? '';
            $this->isSandbox = ($this->paymentGatewayOption['paymob_mode'] ?? 1) == 1;
        }
    }

    public function payment($order, $request): \Illuminate\Http\RedirectResponse
    {
        try {
            // Step 1: Get Auth Token
            $authToken = $this->getAuthToken();
            if (!$authToken) {
                throw new Exception('PayMob authentication failed');
            }

            // Step 2: Register Order
            $paymobOrderId = $this->registerOrder($authToken, $order);
            if (!$paymobOrderId) {
                throw new Exception('Failed to register order with PayMob');
            }

            // Step 3: Get Payment Key
            $paymentKey = $this->getPaymentKey($authToken, $paymobOrderId, $order);
            if (!$paymentKey) {
                throw new Exception('Failed to get payment key from PayMob');
            }

            // Save payment notification
            $capturePaymentNotification = DB::table('capture_payment_notifications')->where([
                ['order_id', $order->id]
            ]);
            $capturePaymentNotification?->delete();

            CapturePaymentNotification::create([
                'order_id'   => $order->id,
                'token'      => $paymobOrderId,
                'created_at' => now()
            ]);

            // Step 4: Redirect to PayMob iframe
            $baseUrl = $this->isSandbox
                ? "https://accept.paymobsolutions.com"
                : "https://accept.paymob.com";

            $iframeUrl = "{$baseUrl}/api/acceptance/iframes/{$this->iframeId}?payment_token={$paymentKey}";

            return redirect()->away($iframeUrl);
        } catch (Exception $e) {
            Log::error('PayMob Payment Error: ' . $e->getMessage());
            return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'paymob'])->with(
                'error',
                $e->getMessage()
            );
        }
    }

    private function getAuthToken(): ?string
    {
        $url = $this->isSandbox
            ? 'https://accept.paymobsolutions.com/api/auth/tokens'
            : 'https://accept.paymob.com/api/auth/tokens';

        $response = Http::post($url, ['api_key' => $this->apiKey]);

        if ($response->successful()) {
            return $response->json('token');
        }

        Log::error('PayMob Auth Error: ' . $response->body());
        return null;
    }

    private function registerOrder(string $authToken, $order): ?int
    {
        $url = $this->isSandbox
            ? 'https://accept.paymobsolutions.com/api/ecommerce/orders'
            : 'https://accept.paymob.com/api/ecommerce/orders';

        $currencyCode = 'EGP';
        $currencyId = Settings::group('site')->get('site_default_currency');
        if (!blank($currencyId)) {
            $currency = Currency::find($currencyId);
            if ($currency) {
                $currencyCode = $currency->code;
            }
        }

        $response = Http::post($url, [
            'auth_token' => $authToken,
            'delivery_needed' => 'false',
            'amount_cents' => (int) ($order->total * 100),
            'currency' => $currencyCode,
            'merchant_order_id' => $order->order_serial_no,
        ]);

        if ($response->successful()) {
            return $response->json('id');
        }

        Log::error('PayMob Register Order Error: ' . $response->body());
        return null;
    }

    private function getPaymentKey(string $authToken, int $orderId, $order): ?string
    {
        $url = $this->isSandbox
            ? 'https://accept.paymobsolutions.com/api/acceptance/payment_keys'
            : 'https://accept.paymob.com/api/acceptance/payment_keys';

        $currencyCode = 'EGP';
        $currencyId = Settings::group('site')->get('site_default_currency');
        if (!blank($currencyId)) {
            $currency = Currency::find($currencyId);
            if ($currency) {
                $currencyCode = $currency->code;
            }
        }

        $user = $order->user;
        $address = $order->orderAddress;

        $response = Http::post($url, [
            'auth_token' => $authToken,
            'amount_cents' => (int) ($order->total * 100),
            'expiration' => 3600,
            'order_id' => $orderId,
            'billing_data' => [
                'email' => $user->email ?? 'customer@example.com',
                'first_name' => $user->first_name ?? $user->name ?? 'Customer',
                'last_name' => $user->last_name ?? 'User',
                'phone_number' => $user->phone ?? '01000000000',
                'street' => $address->address ?? 'NA',
                'city' => $address->city ?? 'Cairo',
                'country' => $address->country ?? 'EG',
                'apartment' => 'NA',
                'floor' => 'NA',
                'building' => 'NA',
                'shipping_method' => 'NA',
                'postal_code' => 'NA',
                'state' => 'NA'
            ],
            'currency' => $currencyCode,
            'integration_id' => (int) $this->integrationId
        ]);

        if ($response->successful()) {
            return $response->json('token');
        }

        Log::error('PayMob Payment Key Error: ' . $response->body());
        return null;
    }

    public function status(): bool
    {
        $paymentGateways = PaymentGateway::where(['slug' => 'paymob', 'status' => Activity::ENABLE])->first();
        if ($paymentGateways) {
            return true;
        }
        return false;
    }

    public function success($order, $request): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::transaction(function () use ($order, $request) {
                // PayMob sends transaction data in callback
                $transactionId = $request->id ?? $request->transaction_id ?? null;
                $success = $request->success ?? $request->data['success'] ?? false;

                if ($success === 'true' || $success === true) {
                    $capturePaymentNotification = DB::table('capture_payment_notifications')->where([
                        ['order_id', $order->id]
                    ]);
                    $token = $capturePaymentNotification->first();

                    if (!blank($token)) {
                        $this->paymentService->payment($order, 'paymob', $transactionId ?? $token->token);
                        $capturePaymentNotification->delete();
                        $this->response = true;
                    }
                }
            });

            if ($this->response) {
                return redirect()->route('payment.successful', ['order' => $order])->with(
                    'success',
                    trans('all.message.payment_successful')
                );
            }

            return redirect()->route('payment.fail', ['order' => $order, 'paymentGateway' => 'paymob'])->with(
                'error',
                trans('all.message.something_wrong')
            );
        } catch (Exception $e) {
            Log::error('PayMob Success Error: ' . $e->getMessage());
            DB::rollBack();
            return redirect()->route('payment.fail', ['order' => $order, 'paymentGateway' => 'paymob'])->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function fail($order, $request): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'paymob'])->with(
            'error',
            trans('all.message.something_wrong')
        );
    }

    public function cancel($order, $request): \Illuminate\Http\RedirectResponse
    {
        return redirect('/checkout/payment');
    }

    /**
     * Verify HMAC signature from PayMob callback
     */
    public function verifyHmac(array $data): bool
    {
        $concatenatedString = $data['amount_cents'] .
            $data['created_at'] .
            $data['currency'] .
            $data['error_occured'] .
            $data['has_parent_transaction'] .
            $data['id'] .
            $data['integration_id'] .
            $data['is_3d_secure'] .
            $data['is_auth'] .
            $data['is_capture'] .
            $data['is_refunded'] .
            $data['is_standalone_payment'] .
            $data['is_voided'] .
            $data['order']['id'] .
            $data['owner'] .
            $data['pending'] .
            $data['source_data']['pan'] .
            $data['source_data']['sub_type'] .
            $data['source_data']['type'] .
            $data['success'];

        $hash = hash_hmac('sha512', $concatenatedString, $this->hmac);

        return $hash === ($data['hmac'] ?? '');
    }
}
