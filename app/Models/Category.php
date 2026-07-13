<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class Category extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name',
        'slug'
    ];

    protected $translatable = [
        'name'
    ];

    protected $casts = [
        'name' => 'array'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
