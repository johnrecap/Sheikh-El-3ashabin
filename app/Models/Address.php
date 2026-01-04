<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = "addresses";
    protected $fillable = [
        'label',
        'user_id',
        'governorate',
        'city',
        'street',
        'building_number',
        'apartment',
        'full_address',
        'phone',
        'landline'
    ];

    protected $casts = [
        'id'              => 'integer',
        'label'           => 'string',
        'user_id'         => 'integer',
        'governorate'     => 'string',
        'city'            => 'string',
        'street'          => 'string',
        'building_number' => 'string',
        'apartment'       => 'string',
        'full_address'    => 'string',
        'phone'           => 'string',
        'landline'        => 'string',
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

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
