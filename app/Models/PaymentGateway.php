<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class PaymentGateway extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;

    protected $table = "payment_gateways";
    protected $fillable = ['name', 'slug', 'misc', 'status'];

    protected $casts = [
        'id'     => 'integer',
        'name'   => 'string',
        'slug'   => 'string',
        'misc'   => 'string',
        'status' => 'integer',
    ];

    public function gatewayOptions()
    {
        return $this->morphMany(GatewayOption::class, 'model');
    }


    public function getImageAttribute(): string
    {
        // 1. First check for direct static file in public folder (Bypasses Storage/Symlink issues)
        // We handle standard names: paymob.png, cashondelivery.png etc.
        $staticPath = 'images/payment-gateways/' . str_replace('-', '', strtolower($this->slug)) . '.png';
        if (file_exists(public_path($staticPath))) {
            return asset($staticPath);
        }

        // 2. Then check Media Library
        $mediaUrl = $this->getFirstMediaUrl('payment-gateway');
        if (!empty($mediaUrl)) {
            return $mediaUrl;
        }

        return asset('images/default/payment-gateway/payment-gateway.png');
    }
}
