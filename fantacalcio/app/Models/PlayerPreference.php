<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerPreference extends Model
{
    protected $fillable = [
        'user_id',
        'player_id',
        'is_target',
        'value',
        'integrity',
        'quality',
        'notes',
        'rank',
    ];

    protected $casts = [
        'user_id' => 'int',
        'player_id' => 'int',
        'is_target' => 'bool',
        'value' => 'int',
        'integrity' => 'int',
        'quality' => 'int',
        'rank' => 'int',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_id', 'id');
    }
}
