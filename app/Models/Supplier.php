<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Google\Service\AndroidPublisher\Resource\Purchases;

class Supplier extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $table = "suppliers";
    protected $fillable = ['company', 'name', 'email', 'country_code', 'phone', 'address'];
    protected $casts = [
        'id'           => 'integer',
        'company'      => 'string',
        'name'         => 'string',
        'email'        => 'string',
        'country_code' => 'string',
        'phone'        => 'string',
        'address'      => 'string',
    ];

    public function getImageAttribute(): string
    {
        if (!empty($this->getFirstMediaUrl('supplier'))) {
            return asset($this->getFirstMediaUrl('supplier'));
        }
        return asset('images/required/profile.png');
    }

    public function purchases()
    {
        $this->hasMany(Purchases::class, 'supplier_id', 'id');
    }
}
