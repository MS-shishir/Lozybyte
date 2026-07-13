<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class Post extends Model
{
    use HasTranslations;

    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'slug',
        'content',
        'image_path',
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
