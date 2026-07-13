<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'logo_path', 'url', 'status', 'sort_order'];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer'
    ];
}
