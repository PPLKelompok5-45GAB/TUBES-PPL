<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'cover_image',
    ];

    public function books()
    {
        // Adjust relationship if many-to-many
        return $this->hasMany(Book::class, 'collection_id');
    }
}
