<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'country_code', 'latitude', 'longitude', 'address', 'status', 'delivery_zone_id'];
    protected $casts = [
        'id'               => 'integer',
        'name'             => 'string',
        'email'            => 'string',
        'phone'            => 'string',
        'country_code'     => 'string',
        'latitude'         => 'string',
        'longitude'        => 'string',
        'address'          => 'string',
        'status'           => 'integer',
        'delivery_zone_id' => 'integer',
    ];
}