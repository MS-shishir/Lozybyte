<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class Team extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name',
        'role',
        'image_path',
        'social_links',
        'status',
        'user_id'
    ];

    protected $translatable = [
        'role'
    ];

    protected $casts = [
        'role' => 'array',
        'social_links' => 'array',
        'status' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
