<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class Product extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'pricing',
        'demo_url',
        'features',
        'screenshots',
        'status',
        'icon',
        'color',
        'tagline',
        'badge',
        'badge_color',
        'description',
        'clients_count',
        'rating',
        'screenshot_type',
        'sort_order'
    ];

    protected $translatable = [
        'title',
        'features',
        'tagline',
        'badge',
        'description'
    ];

    protected $casts = [
        'title' => 'array',
        'pricing' => 'array',
        'features' => 'array',
        'screenshots' => 'array',
        'status' => 'boolean',
        'tagline' => 'array',
        'badge' => 'array',
        'description' => 'array',
        'clients_count' => 'integer',
        'rating' => 'double',
        'sort_order' => 'integer'
    ];
}
