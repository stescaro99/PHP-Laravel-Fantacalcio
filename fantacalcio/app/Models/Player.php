<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Player extends Model
{
    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'position',
        'mantra_position',
        'name',
        'team',
        'quotation',
        'initial_quotation',
        'difference',
        'mantra_quotation',
        'initial_mantra_quotation',
        'mantra_difference',
        'value',
        'mantra_value',
    ];

    protected $casts = [
        'id' => 'int',
        'position' => 'string',
        'mantra_position' => 'string',
        'name' => 'string',
        'team' => 'string',
        'quotation' => 'int',
        'initial_quotation' => 'int',
        'difference' => 'int',
        'mantra_quotation' => 'int',
        'initial_mantra_quotation' => 'int',
        'mantra_difference' => 'int',
        'value' => 'int',
        'mantra_value' => 'int',
    ];


    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function stats(): HasMany
    {
        return $this->hasMany(Stat::class, 'player_id', 'id');
    }

    // Preferenze (uno-a-molti) su player_preferences
    public function preferences(): HasMany
    {
        return $this->hasMany(PlayerPreference::class, 'player_id');
    }

    // Utenti (molti-a-molti) con metadati nel pivot
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'player_preferences', 'player_id', 'user_id')
            ->withPivot(['is_target','value','integrity','quality','notes','rank'])
            ->withTimestamps();
    }

    // Relazione many-to-many con i FantaTeam tramite pivot fanta_team_player
    public function fantaTeams(): BelongsToMany
    {
        return $this->belongsToMany(FantaTeam::class, 'fanta_team_player', 'player_id', 'fanta_team_id')->withTimestamps();
    }
}
