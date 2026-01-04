<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    use HasFactory;

    protected $table = "order_addresses";
    protected $fillable = [
        'order_id',
        'user_id',
        'label',
        'governorate',
        'city',
        'street',
        'building_number',
        'apartment',
        'full_address',
        'phone',
        'address',
        'latitude',
        'longitude',
        'landline'
    ];

    protected $casts = [
        'id'              => 'integer',
        'order_id'        => 'integer',
        'user_id'         => 'integer',
        'label'           => 'string',
        'governorate'     => 'string',
        'city'            => 'string',
        'street'          => 'string',
        'building_number' => 'string',
        'apartment'       => 'string',
        'full_address'    => 'string',
        'phone'           => 'string',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($address) {
            $address->full_address = $address->generateFullAddress();
        });
    }

    public function generateFullAddress(): string
    {
        $parts = array_filter([
            $this->governorate,
            $this->city,
            $this->street,
            $this->building_number ? "عقار رقم {$this->building_number}" : null,
            $this->apartment ? "شقة {$this->apartment}" : null,
        ]);

        return implode('، ', $parts);
    }
}
