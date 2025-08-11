<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    protected $fillable = [
        'id',
        'position',
        'mantra_position',
        'name',
        'team',
        'n_votes',
        'average_vote',
        'average_fantavote',
        'goals',
        'goals_conceded',
        'catched_penalties',
        'taken_penalties',
        'scored_penalties',
        'missed_penalties',
        'assists',
        'yellow_cards',
        'red_cards',
        'own_goals',
    ];

    protected $casts = [
        'id' => 'int',
        'position' => 'string',
        'mantra_position' => 'string',
        'name' => 'string',
        'team' => 'string',
        'n_votes' => 'int',
        'average_vote' => 'float',
        'average_fantavote' => 'float',
        'goals' => 'int',
        'goals_conceded' => 'int',
        'catched_penalties' => 'int',
        'taken_penalties' => 'int',
        'scored_penalties' => 'int',
        'missed_penalties' => 'int',
        'assists' => 'int',
        'yellow_cards' => 'int',
        'red_cards' => 'int',
        'own_goals' => 'int',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
