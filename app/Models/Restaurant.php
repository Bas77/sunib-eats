<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Restaurant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'google_place_id',
        'description',
        'cuisine',
        'area',
        'address',
        'latitude',
        'longitude',
        'rating',
        'price_range',
        'image_url',
        'image_type',
        'is_featured',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'rating' => 'decimal:1',
        'is_featured' => 'boolean',
    ];

    /**
     * Get all of the reviews for the Restaurant.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}