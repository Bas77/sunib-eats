<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}