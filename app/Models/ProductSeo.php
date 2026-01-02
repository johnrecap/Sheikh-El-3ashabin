<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductSeo extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $table = "product_seos";
    protected $fillable = ['product_id', 'title', 'description', 'meta_keyword'];
    protected $casts = [
        'id'           => 'integer',
        'product_id'   => 'integer',
        'title'        => 'string',
        'description'  => 'string',
        'meta_keyword' => 'string',
    ];

    public function getThumbAttribute(): string
    {
        if (!empty($this->getFirstMediaUrl('product-seo'))) {
            $brand = $this->getMedia('product-seo')->last();
            return $brand->getUrl('thumb');
        }
        return asset('images/seo/thumb.png');
    }

    public function getCoverAttribute(): string
    {
        if (!empty($this->getFirstMediaUrl('product-seo'))) {
            $brand = $this->getMedia('product-seo')->last();
            return $brand->getUrl('cover');
        }
        return asset('images/seo/cover.png');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('contain', 75, 48)->keepOriginalImageFormat()->sharpen(10);
        $this->addMediaConversion('cover')->width(400)->keepOriginalImageFormat()->sharpen(10);
    }
}
