<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class Testimonial extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name',
        'designation',
        'company',
        'review',
        'rating',
        'image_path',
        'video_url',
        'video_path',
        'status'
    ];

    protected $translatable = [
        'designation',
        'review'
    ];

    protected $casts = [
        'designation' => 'array',
        'review' => 'array',
        'status' => 'boolean',
        'rating' => 'integer'
    ];
}
