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
        'address',
        'apartment',
        'latitude',
        'longitude'
    ];

    protected $casts = [
        'id'           => 'integer',
        'order_id'     => 'integer',
        'user_id'      => 'integer',
        'label'        => 'string',
        'address'      => 'string',
        'apartment'    => 'string',
        'latitude'     => 'string',
        'longitude'    => 'string',
    ];
}
