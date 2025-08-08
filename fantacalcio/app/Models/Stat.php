<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    protected $fillable = [
        'player_id',
        'season',
        'goals',
        'assists',
        'yellow_cards',
        'red_cards',
        'own_goals',
        'penalties_taken',
        'penalties_scored',
        'penalties_catched',
        'goals_conceded',
    ];

    protected $casts = [
        'player_id' => 'int',
        'goals' => 'int',
        'assists' => 'int',
        'yellow_cards' => 'int',
        'red_cards' => 'int',
        'own_goals' => 'int',
        'penalties_taken' => 'int',
        'penalties_scored' => 'int',
        'penalties_catched' => 'int',
        'goals_conceded' => 'int',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
