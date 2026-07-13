<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EditLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'element_key',
        'action',
        'old_val',
        'new_val',
        'ip_address',
        'user_agent'
    ];

    /**
     * Get the user who made the edit.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
