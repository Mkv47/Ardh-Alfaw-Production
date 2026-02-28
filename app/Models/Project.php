<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title', 'description', 'icon', 'image',
        'category_key', 'category_label',
        'client', 'year', 'sort_order',
    ];
}
