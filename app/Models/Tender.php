<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tender extends Model
{
    protected $fillable = ['title', 'description', 'type', 'status', 'deadline', 'sort_order'];

    protected $casts = [
        'deadline' => 'date',
    ];
}
