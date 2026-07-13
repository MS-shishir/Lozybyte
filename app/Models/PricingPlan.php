<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class PricingPlan extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name', 'category', 'tagline', 'description',
        'price_monthly', 'price_yearly', 'price_lifetime',
        'link_monthly', 'link_yearly', 'link_lifetime',
        'color', 'badge', 'features',
        'is_featured', 'status', 'sort_order'
    ];

    protected $translatable = ['name', 'category', 'tagline', 'description', 'badge', 'features'];

    protected $casts = [
        'name'        => 'array',
        'category'    => 'array',
        'tagline'     => 'array',
        'description' => 'array',
        'badge'       => 'array',
        'features'    => 'array',
        'is_featured' => 'boolean',
        'status'      => 'boolean',
        'sort_order'  => 'integer',
    ];
}
