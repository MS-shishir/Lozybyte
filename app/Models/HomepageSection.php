<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class HomepageSection extends Model
{
    use HasTranslations;

    protected $fillable = [
        'key',
        'title',
        'subtitle',
        'description',
        'short_description',
        'button_text',
        'button_url',
        'background_image',
        'main_image',
        'icon',
        'cards',
        'statistics',
        'colors',
        'sort_order',
        'visible'
    ];

    protected $translatable = [
        'title',
        'subtitle',
        'description',
        'short_description',
        'button_text'
    ];

    protected $casts = [
        'title' => 'array',
        'subtitle' => 'array',
        'description' => 'array',
        'short_description' => 'array',
        'button_text' => 'array',
        'cards' => 'array',
        'statistics' => 'array',
        'colors' => 'array',
        'sort_order' => 'integer',
        'visible' => 'boolean'
    ];
}
