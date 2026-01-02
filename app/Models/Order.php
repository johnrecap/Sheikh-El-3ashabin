<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Order extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table    = "orders";
    protected $fillable = [
        'order_serial_no',
        'user_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'is_guest_order',
        'delivery_zone_id',
        'tax',
        'discount',
        'subtotal',
        'total',
        'delivery_charge',
        'order_type',
        'order_datetime',
        'payment_method',
        'pos_payment_method',
        'pos_payment_note',
        'payment_status',
        'status',
        'reason',
        'edited_amount',
        'edited_date',
        'source',
        'delivery_boy_id'
    ];

    protected $casts = [
        'id'                 => 'integer',
        'order_serial_no'    => 'string',
        'user_id'            => 'integer',
        'guest_name'         => 'string',
        'guest_email'        => 'string',
        'guest_phone'        => 'string',
        'is_guest_order'     => 'boolean',
        'delivery_zone_id'   => 'integer',
        'tax'                => 'decimal:6',
        'discount'           => 'decimal:6',
        'subtotal'           => 'decimal:6',
        'total'              => 'decimal:6',
        'delivery_charge'    => 'decimal:6',
        'order_type'         => 'integer',
        'order_datetime'     => 'datetime',
        'payment_method'     => 'integer',
        'pos_payment_method' => 'integer',
        'pos_payment_note'   => 'string',
        'payment_status'     => 'integer',
        'status'             => 'integer',
        'reason'             => 'string',
        'edited_amount'      => 'double',
        'edited_date'        => 'datetime',
        'source'             => 'integer',
        'delivery_boy_id'    => 'integer'
    ];

    /**
     * Check if this is a guest order
     */
    public function isGuestOrder(): bool
    {
        return $this->is_guest_order || is_null($this->user_id);
    }

    public function getImagesAttribute(): array
    {
        $response = [];
        if (!empty($this->getFirstMediaUrl('order'))) {
            $images = $this->getMedia('order');
            foreach ($images as $image) {
                $response[] = $image['original_url'];
            }
        }
        return $response;
    }

    public function transaction(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    public function orderProducts(): \Illuminate\Database\Eloquent\Relations\morphMany
    {
        return $this->morphMany(Stock::class, 'model');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function address(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(OrderAddress::class);
    }

    public function outletAddress(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(OrderOutletAddress::class);
    }

    public function paymentMethod(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PaymentGateway::class, 'payment_method');
    }

    public function deliveryBoy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'delivery_boy_id', 'id');
    }

    public function scopePending($query)
    {
        return $query->where('status', OrderStatus::PENDING);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', OrderStatus::CONFIRMED);
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', OrderStatus::ON_THE_WAY);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', OrderStatus::DELIVERED);
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', OrderStatus::CANCELED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', OrderStatus::REJECTED);
    }
    public function scopeReturned($query)
    {
        return $query->where('status', OrderStatus::RETURNED);
    }
    public function deliveryZone(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DeliveryZone::class);
    }
}
