<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'location',
        'avg_rating',
        'avg_price',
        'total_reviews',
        'image_url',
        'approved',
        'hot_score',
    ];

    // Satu restaurant bisa punya banyak promo
    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }

    // Satu restaurant bisa punya banyak review
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Satu restaurant bisa punya banyak kategori
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_restaurant');
    }

    // Logic untuk ngasih simbol harga berdasarkan avg_price
    public function getPriceSymbolAttribute()
    {
        $price = $this->avg_price;
        if ($price < 30000) return '$';
        if ($price < 50000) return '$$';
        if ($price < 100000) return '$$$';
        if ($price < 250000) return '$$$$';
        return '$$$$$';
    }

    // Scope untuk search restaurant berdasarkan nama atau lokasi
    public function scopeSearch(Builder $query, $search)
    {
        return $query->when($search, function ($q) use ($search) {
            $q->where(function ($subQ) use ($search) {
                $subQ->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        });
    }

    // Scope untuk filter restaurant berdasarkan range harga
    public function scopeFilterPrice(Builder $query, $level)
    {
        if (!$level) return $query;

        return $query->where(function ($q) use ($level) {
            if ($level == '1')      $q->where('avg_price', '<', 30000);
            elseif ($level == '2')  $q->whereBetween('avg_price', [30000, 49999]);
            elseif ($level == '3')  $q->whereBetween('avg_price', [50000, 99999]);
            elseif ($level == '4')  $q->whereBetween('avg_price', [100000, 249999]);
            elseif ($level == '5')  $q->where('avg_price', '>=', 250000);
        });
    }

    // Scope untuk filter restaurant berdasarkan kategori
    public function scopeFilterByCategories(Builder $query, $categories)
    {
        return $query->when($categories, function ($q) use ($categories) {
            $catsData = Category::whereIn('id', $categories)->get()->groupBy('type');

            foreach ($catsData as $type => $cats) {
                $ids = $cats->pluck('id');
                $q->whereHas('categories', function ($subQ) use ($ids) {
                    $subQ->whereIn('categories.id', $ids);
                });
            }
        });
    }

    // Scope untuk sorting restaurant berdasarkan kriteria tertentu
    public function scopeSortBy(Builder $query, $sort)
    {
        return $query->when($sort, function ($q) use ($sort) {
            if ($sort == 'rating_desc') return $q->orderByDesc('avg_rating');
            if ($sort == 'price_asc') return $q->orderBy('avg_price', 'asc');
            if ($sort == 'price_desc') return $q->orderByDesc('avg_price');
            if ($sort == 'newest') return $q->latest();
            return $q;
        }, function ($q) {
            return $q->inRandomOrder();
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
