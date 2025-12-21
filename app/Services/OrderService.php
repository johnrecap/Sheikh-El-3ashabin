<?php

namespace App\Services;


use App\Enums\Activity;
use App\Enums\DiscountType;
use App\Http\Requests\OrderProductDeleteRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Libraries\AppLibrary;
use App\Models\OrderCoupon;
use Exception;
use App\Models\User;
use App\Enums\Status;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Product;
use App\Enums\OrderType;
use App\Models\StockTax;
use App\Enums\OrderStatus;
use App\Models\Transaction;
use App\Enums\PaymentStatus;
use App\Events\SendOrderSms;
use Illuminate\Http\Request;
use App\Events\SendOrderMail;
use App\Events\SendOrderPush;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\PosOrderRequest;
use App\Events\SendOrderDeliveryBoySms;
use App\Events\SendOrderDeliveryBoyMail;
use App\Events\SendOrderDeliveryBoyPush;
use App\Http\Requests\OrderStatusRequest;
use App\Http\Requests\PaymentStatusRequest;
use App\Libraries\QueryExceptionLibrary;
use Carbon\Carbon;

class OrderService
{
    public object $order;
    protected array $orderFilter = [
        'order_serial_no',
        'user_id',
        'total',
        'order_type',
        'order_datetime',
        'payment_method',
        'payment_status',
        'status',
        'active',
        'delivery_boy_id',
        'source'
    ];

    protected array $exceptFilter = [
        'excepts'
    ];

    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request)
    {
        try {
            $requests = $request->all();
            $method = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType = $request->get('order_by') ?? 'desc';

            return Order::with('transaction', 'orderProducts')->where(function ($query) use ($requests) {
                if (isset($requests['from_date']) && isset($requests['to_date'])) {
                    $first_date = Date('Y-m-d', strtotime($requests['from_date']));
                    $last_date = Date('Y-m-d', strtotime($requests['to_date']));
                    $query->whereDate('order_datetime', '>=', $first_date)->whereDate(
                        'order_datetime',
                        '<=',
                        $last_date
                    );
                }

                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->orderFilter)) {
                        if ($key === "status") {
                            $query->where($key, (int) $request);
                        } else if ($key === 'payment_method' && (int)$request < 0) {
                            $query->where('pos_payment_method', abs($request));
                        } else if ($key === 'payment_method' && $request == 1) {
                            $query->where('order_type', '!=', OrderType::POS)->where('payment_method', 1);
                        } else if ($key === 'source') {
                            $query->where($key, $request);
                        } else {
                            $query->where($key, 'like', '%' . $request . '%');
                        }
                    }

                    if (in_array($key, $this->exceptFilter)) {
                        $explodes = explode('|', $request);
                        if (is_array($explodes)) {
                            foreach ($explodes as $explode) {
                                $query->where('order_type', '!=', $explode);
                            }
                        }
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function myOrder(PaginateRequest $request)
    {
        try {
            $requests = $request->all();
            $method = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType = $request->get('order_by') ?? 'desc';

            return Order::where('order_type', "!=", OrderType::POS)->where(function ($query) use ($requests) {
                $query->where('user_id', auth()->user()->id);
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->orderFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                    if (in_array($key, $this->exceptFilter)) {
                        $explodes = explode('|', $request);
                        if (is_array($explodes)) {
                            foreach ($explodes as $explode) {
                                $query->where('status', '!=', $explode);
                            }
                        }
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function userOrder(PaginateRequest $request, User $user)
    {
        try {
            $requests = $request->all();
            $method = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType = $request->get('order_by') ?? 'desc';

            return Order::where('order_type', "!=", OrderType::POS)->where(function ($query) use ($requests, $user) {
                $query->where('user_id', $user->id);
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->orderFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                    if (in_array($key, $this->exceptFilter)) {
                        $explodes = explode('|', $request);
                        if (is_array($explodes)) {
                            foreach ($explodes as $explode) {
                                $query->where('status', '!=', $explode);
                            }
                        }
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }


    /**
     * @throws Exception
     */
    public function posOrderStore(PosOrderRequest $request): object
    {
        try {
            DB::transaction(function () use ($request) {
                $this->order = Order::create(
                    $request->validated() + [
                        'user_id' => $request->customer_id,
                        'status' => OrderStatus::CONFIRMED,
                        'payment_status' => PaymentStatus::PAID,
                        'order_datetime' => date('Y-m-d H:i:s')
                    ]
                );

                $products = json_decode($request->products);
                if (!blank($products)) {
                    foreach ($products as $product) {
                        $stockId = Stock::create([
                            'product_id' => $product->product_id,
                            'model_type' => Order::class,
                            'model_id' => $this->order->id,
                            'item_type' => $product->variation_id > 0 ? ProductVariation::class : Product::class,
                            'item_id' => $product->variation_id > 0 ? $product->variation_id : $product->product_id,
                            'variation_names' => $product->variation_names,
                            'sku' => $product->sku,
                            'price' => $product->price,
                            'quantity' => -$product->quantity,
                            'discount' => $product->discount,
                            'tax' => number_format($product->total_tax, env('CURRENCY_DECIMAL_POINT'), '.', ''),
                            'subtotal' => $product->subtotal,
                            'total' => $product->total,
                            'status' => Status::ACTIVE,
                        ]);
                        if ($product->taxes) {
                            $j = 0;
                            $productTaxArray = [];
                            foreach ($product->taxes as $tax) {
                                $productTaxArray[$j] = [
                                    'stock_id' => $stockId->id,
                                    'product_id' => $product->product_id,
                                    'tax_id' => $tax->id,
                                    'name' => $tax->name,
                                    'code' => $tax->code,
                                    'tax_rate' => $tax->tax_rate,
                                    'tax_amount' => $tax->tax_amount,
                                    'created_at' => now(),
                                    'updated_at' => now()
                                ];
                                $j++;
                            }
                            StockTax::insert($productTaxArray);
                        }
                    }
                }

                $this->order->order_serial_no = date('dmy') . $this->order->id;
                $this->order->save();
            });
            return $this->order;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(Order $order, $auth = false): Order|array
    {
        try {
            if ($auth) {
                if ($order->user_id == Auth::user()->id) {
                    return $order;
                } else {
                    return [];
                }
            } else {
                return $order;
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function orderDetails(User $user, Order $order): Order|array
    {
        try {
            if ($order->user_id == $user->id) {
                return $order;
            } else {
                return [];
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function changeStatus(Order $order, OrderStatusRequest $request, $auth = false): Order|array
    {
        try {
            if ($auth) {
                if ($order->user_id == Auth::user()->id) {
                    if ($request->reason) {
                        $order->reason = $request->reason;
                    }

                    if ($request->status == OrderStatus::REJECTED || $request->status == OrderStatus::CANCELED) {
                        if ($order->transaction) {
                            app(PaymentService::class)->cashBack(
                                $order,
                                'credit',
                                rand(111111111111111, 99999999999999)
                            );
                        }
                    }
                    SendOrderMail::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                    SendOrderSms::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                    SendOrderPush::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                    $order->status = $request->status;
                    $order->save();
                }
            } else {
                if ($request->status == OrderStatus::REJECTED || $request->status == OrderStatus::CANCELED) {
                    $request->validate([
                        'reason' => 'required|max:700',
                    ]);

                    if ($request->reason) {
                        $order->reason = $request->reason;
                    }

                    if ($order->transaction) {
                        app(PaymentService::class)->cashBack(
                            $order,
                            'credit',
                            rand(111111111111111, 99999999999999)
                        );
                    }
                }
                if ($request->status == OrderStatus::RETURNED) {
                    $request->validate([
                        'reason' => 'required|max:700',
                    ]);

                    if ($request->reason) {
                        $order->reason = $request->reason;
                    }
                }

                if ($request->status == OrderStatus::CONFIRMED) {
                    Stock::where(['model_id' => $order->id, 'model_type' => Order::class, 'status' => Status::INACTIVE])?->update(['status' => Status::ACTIVE]);
                }
                SendOrderMail::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                SendOrderSms::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                SendOrderPush::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                $order->status = $request->status;
                $order->save();
            }
            return $order;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function changePaymentStatus(Order $order, PaymentStatusRequest $request, $auth = false): Order|array
    {
        try {
            if ($auth) {
                if ($order->user_id == Auth::user()->id) {
                    $order->payment_status = $request->payment_status;
                    $order->save();
                    return $order;
                } else {
                    return [];
                }
            } else {
                $order->payment_status = $request->payment_status;
                $order->save();
                return $order;
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function selectDeliveryBoy(Order $order, Request $request, $auth = false): Order|array
    {
        try {
            if ($auth) {
                if ($order->user_id == Auth::user()->id) {
                    $order->delivery_boy_id = $request->delivery_boy_id;
                    $order->save();
                    SendOrderDeliveryBoyMail::dispatch(['order_id' => $order->id, 'status' => 101]);
                    SendOrderDeliveryBoySms::dispatch(['order_id' => $order->id, 'status' => 101]);
                    SendOrderDeliveryBoyPush::dispatch(['order_id' => $order->id, 'status' => 101]);
                    return $order;
                } else {
                    return [];
                }
            } else {
                $order->delivery_boy_id = $request->delivery_boy_id;
                $order->save();
                SendOrderDeliveryBoyMail::dispatch(['order_id' => $order->id, 'status' => 101]);
                SendOrderDeliveryBoySms::dispatch(['order_id' => $order->id, 'status' => 101]);
                SendOrderDeliveryBoyPush::dispatch(['order_id' => $order->id, 'status' => 101]);
                return $order;
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(Order $order): void
    {
        try {
            DB::transaction(function () use ($order) {
                if ($order?->orderProducts) {
                    $stockIds = $order?->orderProducts->pluck('id');
                    if (!blank($stockIds)) {
                        StockTax::whereIn('stock_id', $stockIds)->delete();
                    }
                    $order?->orderProducts()->delete();
                }
                $order->delete();
            });
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }


    /**
     * @throws Exception
     */
    public function deliveryBoyOrder(PaginateRequest $request, $auth = false)
    {
        try {
            $requests = $request->all();
            $method = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType = $request->get('order_by') ?? 'desc';

            return Order::where('order_type', "!=", OrderType::POS)->where('delivery_boy_id', Auth::user()->id)->where(
                function ($query) use ($requests) {
                    if (isset($requests['from_date']) && isset($requests['to_date'])) {
                        $first_date = Date('Y-m-d', strtotime($requests['from_date']));
                        $last_date = Date('Y-m-d', strtotime($requests['to_date']));
                        $query->whereDate('order_datetime', '>=', $first_date)->whereDate(
                            'order_datetime',
                            '<=',
                            $last_date
                        );
                    }
                    foreach ($requests as $key => $request) {
                        if (in_array($key, $this->orderFilter)) {
                            $query->where($key, 'like', '%' . $request . '%');
                        }
                        if (in_array($key, $this->exceptFilter)) {
                            $explodes = explode('|', $request);
                            if (is_array($explodes)) {
                                foreach ($explodes as $explode) {
                                    $query->where('status', '!=', $explode);
                                }
                            }
                        }
                    }
                }
            )->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function deliveryBoOrderSummary(Request $request)
    {
        try {
            $order = new Order;
            if ($request->first_date && $request->last_date) {
                $first_date = Date('Y-m-d', strtotime($request->first_date));
                $last_date  = Date('Y-m-d', strtotime($request->last_date));
            } else {
                $first_date = Date('Y-m-01', strtotime(Carbon::today()->toDateString()));
                $last_date  = Date('Y-m-t', strtotime(Carbon::today()->toDateString()));
            }

            $orderSummaryArray = [];

            $total_delivered = $order->where(['delivery_boy_id' => $request->delivery_boy_id, 'status' => OrderStatus::DELIVERED])->whereDate('order_datetime', '>=', $first_date)->whereDate('order_datetime', '<=', $last_date)->count();
            $total_returned  = $order->where(['delivery_boy_id' => $request->delivery_boy_id, 'status' => OrderStatus::RETURNED])->whereDate('order_datetime', '>=', $first_date)->whereDate('order_datetime', '<=', $last_date)->count();

            if ($total_delivered > 0) {
                $orderSummaryArray["delivered"] = (int) $total_delivered;
            } else {
                $orderSummaryArray["delivered"] = 0;
            }

            if ($total_returned > 0) {
                $orderSummaryArray["returned"] = (int) $total_returned;
            } else {
                $orderSummaryArray["returned"] = 0;
            }

            return $orderSummaryArray;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function deliveryBoyOrderDetails(Order $order): Order|array
    {
        try {
            if ($order->delivery_boy_id == Auth::user()->id) {
                return $order;
            } else {
                return [];
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function deliveryBoyOrderCount(): array
    {
        try {
            $order = new Order;
            $orderCountArray = [];
            $orderCountArray['total_delivered'] = $order->where(
                ['delivery_boy_id' => Auth::user()->id, 'status' => OrderStatus::DELIVERED]
            )->count();
            $orderCountArray['total_returned'] = $order->where(
                ['delivery_boy_id' => Auth::user()->id, 'status' => OrderStatus::RETURNED]
            )->count();

            return $orderCountArray;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function deliveryBoyOrderChangeStatus(Order $order): Order
    {
        try {
            $transaction = Transaction::where('order_id', $order->id)->first();

            if (!$transaction && $order->payment_status == PaymentStatus::UNPAID) {
                $order->payment_status = PaymentStatus::PAID;
            }
            SendOrderMail::dispatch(['order_id' => $order->id, 'status' => OrderStatus::DELIVERED]);
            SendOrderSms::dispatch(['order_id' => $order->id, 'status' => OrderStatus::DELIVERED]);
            SendOrderPush::dispatch(['order_id' => $order->id, 'status' => OrderStatus::DELIVERED]);
            $order->status = OrderStatus::DELIVERED;
            $order->save();
            return $order;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function deliveredOrder(PaginateRequest $request, User $user)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_by') ?? 'desc';

            return Order::where('delivery_boy_id', $user->id)->where('order_type', "!=", OrderType::POS)->where(
                function ($query) use ($requests) {
                    if (isset($requests['from_date']) && isset($requests['to_date'])) {
                        $first_date = Date('Y-m-d', strtotime($requests['from_date']));
                        $last_date  = Date('Y-m-d', strtotime($requests['to_date']));
                        $query->whereDate('order_datetime', '>=', $first_date)->whereDate(
                            'order_datetime',
                            '<=',
                            $last_date
                        );
                    }
                    foreach ($requests as $key => $request) {
                        if (in_array($key, $this->orderFilter)) {
                            $query->where($key, 'like', '%' . $request . '%');
                        }
                        if (in_array($key, $this->exceptFilter)) {
                            $explodes = explode('|', $request);
                            if (is_array($explodes)) {
                                foreach ($explodes as $explode) {
                                    $query->where('status', '!=', $explode);
                                }
                            }
                        }
                    }
                }
            )->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function deliveryBoyDeliveredOrderDetails(User $user, Order $order): Order|array
    {
        try {
            if ($order->delivery_boy_id == $user->id) {
                return $order;
            } else {
                return [];
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }


    public function updateProductQuantity(Order $order, OrderUpdateRequest $request)
    {
        try {
            if ($order->status !== OrderStatus::PENDING) {
                return response(['status' => false, 'message' => trans('all.message.accepted_order_can_not_be_edit')], 422);
            }

            $stock = Stock::with('product')->where('id', $request->stock_id)->first();
            if ($stock->product->can_purchasable == Activity::ENABLE) {
                // check stock
                $availability = Stock::where([
                    'product_id' => $stock->product_id,
                    'item_type' => $stock->item_type,
                    'item_id' => $stock->item_id,
                    'status' => Status::ACTIVE,
                ])
                    ->where('id', '!=', $stock->id)
                    ->sum('quantity');

                if ((float) $availability < (float) $request->quantity) {
                    return response(['status' => false, 'message' => trans('all.message.out_of_stock')], 422);
                }
            }

            DB::transaction(function () use ($request, $stock) {
                // Calculate Tax , Subtotal , Total
                $total_tax_rate = StockTax::where(['stock_id' => $request->stock_id, 'product_id' => $request->product_id])
                    ->sum('tax_rate');
                $stock_subtotal = $stock->price * $request->quantity;
                $stock_tax = ($total_tax_rate / 100) * $stock_subtotal;
                $stock_total = $stock_subtotal + $stock_tax;

                // Update stock 
                $stock->tax = $stock_tax;
                $stock->subtotal = $stock_subtotal;
                $stock->total = $stock_total;
                $stock->quantity = -$request->quantity;
                $stock->save();

                // Update Stock Taxes
                $stock_taxes = StockTax::where(['stock_id' => $request->stock_id, 'product_id' => $request->product_id])->get();
                foreach ($stock_taxes as $tax) {
                    $tax->tax_amount = ($tax->tax_rate / 100) * $stock_subtotal;
                    $tax->save();
                }

                // Calculate Order subtotal , tax : order_products_sum_subtotal, order_products_sum_tax
                $order = Order::withSum('orderProducts', 'subtotal')
                    ->withSum('orderProducts', 'tax')
                    ->where('id', $request->order_id)
                    ->first();

                $discount_total = is_numeric($order->discount) ? $order->discount : 0;
                // Check Coupon for discount . 
                $order_coupon = OrderCoupon::with('coupon')->where('order_id', $request->order_id)->first();
                if ($order_coupon && isset($order_coupon->coupon)) {
                    // Check if discount type is in Percentage
                    if ($order_coupon->coupon?->discount_type == DiscountType::PERCENTAGE) {
                        $discount_total = ($order_coupon->coupon?->discount / 100) * $order->order_products_sum_subtotal;
                        $order_coupon->discount = $discount_total;
                        $order_coupon->save();
                    }
                }

                //Update Order 
                if ($order->edited_amount == 0) {
                    $order->edited_amount = ($order->order_products_sum_subtotal + $order->order_products_sum_tax + $order->delivery_charge - $discount_total) - $order->total;
                } else {
                    $total = $order->total - $order->edited_amount;
                    $order->edited_amount =  ($order->order_products_sum_subtotal + $order->order_products_sum_tax + $order->delivery_charge - $discount_total) - $total;
                }
                $order->edited_date = Carbon::now()->format('Y-m-d H:i:s');
                $order->subtotal    = $order->order_products_sum_subtotal;
                $order->tax         = $order->order_products_sum_tax;
                $order->discount    = $discount_total;
                $order->total       = $order->order_products_sum_subtotal + $order->order_products_sum_tax + $order->delivery_charge - $discount_total;

                $order->save();
            });

            return response(['status' => true, 'message' => trans('all.message.order_updated_successfully')], 200);
        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function deleteProduct(Order $order, OrderUpdateRequest $request)
    {
        try {
            // Only Pending order is editable
            if ($order->status !== OrderStatus::PENDING) {
                return response(['status' => false, 'message' => trans('all.message.accepted_order_can_not_be_edit')], 422);
            }

            $product_count = Order::where('id', $request->order_id)
                ->withCount('orderProducts')
                ->pluck('order_products_count')
                ->first();

            if ($product_count < 2) {
                return response(['status' => false, 'message' => trans('all.message.the_product_can_not_be_deleted')], 422);
            }


            DB::transaction(function () use ($request) {


                // Delete taxes of the product
                $stock_taxes = StockTax::where(['stock_id' => $request->stock_id, 'product_id' => $request->product_id])->pluck('id');
                StockTax::whereIn('id', $stock_taxes)->delete();

                // Delete the product from stock
                Stock::where('id', $request->stock_id)->delete();

                // Calculate Order subtotal , tax : order_products_sum_subtotal, order_products_sum_tax
                $order = Order::withSum('orderProducts', 'subtotal')
                    ->withSum('orderProducts', 'tax')
                    ->where('id', $request->order_id)
                    ->first();

                $discount_total = is_numeric($order->discount) ? $order->discount : 0;
                // Check Coupon for discount . 
                $order_coupon = OrderCoupon::with('coupon')->where('order_id', $request->order_id)->first();
                if ($order_coupon && isset($order_coupon->coupon)) {
                    // Check if discount type is in Percentage
                    if ($order_coupon->coupon?->discount_type == DiscountType::PERCENTAGE) {
                        $discount_total = ($order_coupon->coupon?->discount / 100) * $order->order_products_sum_subtotal;
                        $order_coupon->discount = $discount_total;
                        $order_coupon->save();
                    }
                }

                //Update Order
                if ($order->edited_amount == 0) {
                    $order->edited_amount = ($order->order_products_sum_subtotal + $order->order_products_sum_tax + $order->delivery_charge - $discount_total) - $order->total;
                } else {
                    $total = $order->total - $order->edited_amount;
                    $order->edited_amount =  ($order->order_products_sum_subtotal + $order->order_products_sum_tax + $order->delivery_charge - $discount_total) - $total;
                }
                $order->edited_date = Carbon::now()->format('Y-m-d H:i:s');
                $order->subtotal = $order->order_products_sum_subtotal;
                $order->tax = $order->order_products_sum_tax;
                $order->discount = $discount_total;
                $order->total = $order->order_products_sum_subtotal + $order->order_products_sum_tax + $order->delivery_charge - $discount_total;
                $order->save();
            });

            return response(['status' => true, 'message' => trans('all.message.order_updated_successfully')], 200);
        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }
}
