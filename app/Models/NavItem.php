<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class NavItem extends Model
{
    use HasTranslations;

    protected $fillable = [
        'parent_id',
        'label',
        'url',
        'order',
        'is_external',
        'status'
    ];

    protected $translatable = [
        'label'
    ];

    protected $casts = [
        'label' => 'array',
        'is_external' => 'boolean',
        'status' => 'boolean',
        'order' => 'integer'
    ];

    public function parent()
    {
        return $this->belongsTo(NavItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(NavItem::class, 'parent_id')->orderBy('order', 'asc');
    }
}
