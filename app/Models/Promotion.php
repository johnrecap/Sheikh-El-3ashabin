<?php

namespace App\Models;


use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class Promotion extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = "promotions";
    protected $fillable = ['name', 'slug', 'type', 'status'];
    protected $casts = [
        'id'     => 'integer',
        'name'   => 'string',
        'slug'   => 'string',
        'type'   => 'integer',
        'status' => 'integer'
    ];


    public function getCoverAttribute(): string
    {
        if (!empty($this->getFirstMediaUrl('promotion'))) {
            $promotion = $this->getMedia('promotion')->last();
            return $promotion->getUrl('cover');
        }
        return asset('images/default/promotion/cover.png');
    }

    public function getPreviewAttribute(): string
    {
        if (!empty($this->getFirstMediaUrl('promotion'))) {
            $product = $this->getMedia('promotion')->last();
            return $product->getUrl('preview');
        }
        return asset('images/default/promotion/preview.png');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('cover')->fit('contain', 360, 224)->keepOriginalImageFormat()->sharpen(10);
        $this->addMediaConversion('preview')->width(1126)->height(400)->keepOriginalImageFormat()->sharpen(10);
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'promotion_products');
    }

    public function promotionProducts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PromotionProduct::class, 'promotion_id', 'id');
    }
}
