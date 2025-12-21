<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewImage extends Model
{
    protected $fillable = ['review_id', 'path'];

    // Satu gambar pasti punya satu review
    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
