<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class Portfolio extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title',
        'slug',
        'client',
        'industry',
        'challenge',
        'solution',
        'result',
        'image_path',
        'meta',
        'status'
    ];

    protected $translatable = [
        'title',
        'industry',
        'challenge',
        'solution',
        'result'
    ];

    protected $casts = [
        'title' => 'array',
        'industry' => 'array',
        'challenge' => 'array',
        'solution' => 'array',
        'result' => 'array',
        'meta' => 'array',
        'status' => 'boolean'
    ];
}
