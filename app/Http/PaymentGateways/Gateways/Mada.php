<?php

namespace App\Http\PaymentGateways\Gateways;

use Exception;
use App\Enums\Ask;
use App\Enums\Status;
use App\Models\Order;
use App\Models\Stock;
use App\Enums\Activity;
use App\Models\PaymentGateway;
use App\Services\PaymentService;
use App\Services\PaymentAbstract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Smartisan\Settings\Facades\Settings;
use App\Models\CapturePaymentNotification;

class Mada extends PaymentAbstract
{
    public bool $response = false;

    public function __construct()
    {
        $paymentService = new PaymentService();
        parent::__construct($paymentService);
    }

    public function payment($order, $request): \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\View
    {
        try {
            $paymentGateway = PaymentGateway::where(['slug' => 'mada', 'status' => Activity::ENABLE])->first();

            if (!$paymentGateway) {
                return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'mada'])->with('error', trans('all.message.something_wrong'));
            }

            $gatewayOptions = $this->gatewayOptions($paymentGateway);

            if ($gatewayOptions['mada_status'] == Activity::ENABLE) {
                // Clear any existing payment notification
                $capturePaymentNotification = DB::table('capture_payment_notifications')->where([
                    ['order_id', $order->id]
                ]);
                $capturePaymentNotification?->delete();

                // Generate token for payment verification
                $token = rand(111111111, 999999999);
                CapturePaymentNotification::create([
                    'order_id'   => $order->id,
                    'token'      => $token,
                    'created_at' => now()
                ]);

                // Get phone number and account name from gateway options
                $phoneNumber = $gatewayOptions['mada_phone_number'] ?? '';
                $accountName = $gatewayOptions['mada_account_name'] ?? '';

                // Return view with payment info
                return view('payment.manual-payment', [
                    'order' => $order,
                    'gateway' => 'mada',
                    'gatewayName' => 'Mada',
                    'phoneNumber' => $phoneNumber,
                    'accountName' => $accountName,
                    'token' => $token,
                    'amount' => $order->total,
                    'currency' => Settings::group('site')->get('site_default_currency_symbol') ?? 'ر.س',
                ]);
            } else {
                return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'mada'])->with('error', trans('all.message.something_wrong'));
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'mada'])->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function status(): bool
    {
        $paymentGateways = PaymentGateway::where(['slug' => 'mada', 'status' => Activity::ENABLE])->first();
        if ($paymentGateways) {
            return true;
        }
        return false;
    }

    public function success($order, $request): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::transaction(function () use ($order, $request) {
                if ($request->token) {
                    $capturePaymentNotification = DB::table('capture_payment_notifications')->where([
                        ['token', $request->token]
                    ]);
                    $token                      = $capturePaymentNotification->first();

                    if (!blank($token) && $order->id == $token->order_id) {
                        $order->active = Ask::YES;
                        $order->save();
                        $capturePaymentNotification->delete();
                        $this->response = true;
                    }
                }
            });

            if ($this->response && $order->active == Ask::YES) {
                return redirect()->route('payment.successful', ['order' => $order])->with('success', trans('all.message.payment_successful'));
            }
            return redirect()->route('payment.fail', ['order' => $order, 'paymentGateway' => 'mada'])->with('error', trans('all.message.something_wrong'));
        } catch (Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return redirect()->route('payment.fail', ['order' => $order, 'paymentGateway' => 'mada'])->with('error', $e->getMessage());
        }
    }

    public function fail($order, $request): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'mada'])->with('error', trans('all.message.something_wrong'));
    }

    public function cancel($order, $request): \Illuminate\Foundation\Application|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        return redirect('/checkout/payment');
    }

    private function gatewayOptions($paymentGateway): array
    {
        $options = [];
        if ($paymentGateway->gatewayOptions) {
            foreach ($paymentGateway->gatewayOptions as $gatewayOption) {
                $options[$gatewayOption->option] = $gatewayOption->value;
            }
        }
        return $options;
    }
}
