<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FantaTeam extends Model
{
	public $incrementing = true;
	protected $keyType = 'int';

	protected $fillable = [
		'id',
		'name',
		'user_id',
		'budget',
	];

	protected $casts = [
		'id' => 'int',
		'name' => 'string',
		'user_id' => 'int',
		'budget' => 'int',
	];

	protected $hidden = [
		'created_at',
		'updated_at',
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

	public function players(): BelongsToMany
	{
		return $this->belongsToMany(Player::class, 'fanta_team_player', 'fanta_team_id', 'player_id')->withTimestamps();
	}
}