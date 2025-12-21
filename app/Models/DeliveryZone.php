<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    use HasFactory;
    protected $table = "delivery_zones";
    protected $fillable = ['name', 'email', 'phone', 'latitude', 'longitude', 'address', 'delivery_radius_kilometer', 'delivery_charge_per_kilo', 'minimum_order_amount', 'status'];
    protected $casts = [
        'id'                        => 'integer',
        'name'                      => 'string',
        'email'                     => 'string',
        'phone'                     => 'string',
        'latitude'                  => 'string',
        'longitude'                 => 'string',
        'address'                   => 'string',
        'delivery_radius_kilometer' => 'string',
        'delivery_charge_per_kilo'  => 'decimal:6',
        'minimum_order_amount'      => 'decimal:6',
        'status'                    => 'integer',
    ];
}