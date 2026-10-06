<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $fillable = ['name', 'category', 'description', 'latitude', 'longitude', 'image'];
    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return 'https://placehold.co/600x400?text=No+Image';
        }
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }
        return '/storage/' . ltrim($this->image, '/');    }
}
