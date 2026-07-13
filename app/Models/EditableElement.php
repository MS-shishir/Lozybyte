<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EditableElement extends Model
{
    protected $fillable = [
        'element_key',
        'content',
        'styles',
        'settings',
        'metadata',
    ];

    protected $casts = [
        'content' => 'array',
        'styles' => 'array',
        'settings' => 'array',
        'metadata' => 'array',
    ];
}
