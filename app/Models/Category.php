<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type'];

    // Satu kategori bisa ada di banyak review, dan satu review bisa punya banyak kategori
    public function reviews()
    {
        return $this->belongsToMany(Review::class, 'category_review'); // Many to many
    }

    // Satu kategori bisa dimiliki banyak restoran
    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class, 'category_restaurant'); // Many to many
    }
}
