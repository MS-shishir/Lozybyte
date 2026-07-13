<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class Service extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title',
        'slug',
        'icon',
        'color',
        'glow_color',
        'image_path',
        'description',
        'details',
        'timeline',
        'starting_price',
        'case_result',
        'features',
        'process_steps',
        'sort_order',
        'status',
    ];

    protected $translatable = [
        'title',
        'description',
        'details',
        'case_result',
        'features',
        'process_steps',
    ];

    protected $casts = [
        'title'         => 'array',
        'description'   => 'array',
        'details'       => 'array',
        'case_result'   => 'array',
        'features'      => 'array',
        'process_steps' => 'array',
        'status'        => 'boolean',
        'sort_order'    => 'integer',
    ];
}
