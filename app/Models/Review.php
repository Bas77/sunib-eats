<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'restaurant_id',
        'content',
        'rating',
        'price_per_portion',
        'price_symbol_count',
        'edited_at',
        'edit_history'
    ];

    protected $casts = [
        'edit_history' => 'array',
        'edited_at' => 'datetime',
    ];

    // Satu review pasti punya satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Satu review pasti punya satu restaurant
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    // Satu review bisa punya banyak kategori
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_review');
    }

    // Satu review bisa punya banyak gambar
    public function images()
    {
        return $this->hasMany(ReviewImage::class);
    }

    // Satu review bisa punya banyak vote
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    // Menghitung skor total dari vote
    public function getScoreAttribute()
    {
        return $this->votes->sum('vote_value');
    }

    // Mendapatkan vote user saat ini untuk review ini
    public function getCurrentUserVoteAttribute()
    {
        if (!Auth::check()) return 0;
        return $this->votes->where('user_id', Auth::id())->value('vote_value') ?? 0;
    }
}
