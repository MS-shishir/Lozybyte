<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = ['question', 'answer', 'status', 'sort_order'];

    protected $casts = [
        'question' => 'json',
        'answer' => 'json',
        'status' => 'boolean',
        'sort_order' => 'integer'
    ];
}
