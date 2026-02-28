<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'title', 'excerpt', 'body',
        'icon', 'image', 'badge', 'category',
        'published_at', 'sort_order',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
