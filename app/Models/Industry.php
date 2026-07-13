<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class Industry extends Model
{
    use HasTranslations;

    protected $fillable = [
        'slug',
        'icon',
        'title',
        'description',
        'sort_order',
        'status'
    ];

    protected $translatable = [
        'title',
        'description'
    ];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'status' => 'boolean',
        'sort_order' => 'integer'
    ];
}
