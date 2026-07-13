<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class Page extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title',
        'slug',
        'banner_image',
        'content',
        'seo',
        'status'
    ];

    protected $translatable = [
        'title',
        'content'
    ];

    protected $casts = [
        'title' => 'array',
        'content' => 'array',
        'seo' => 'array',
        'status' => 'boolean'
    ];
}
