<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Player;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // Chiave primaria esplicita
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function playerPreferences(): HasMany
    {
        return $this->hasMany(PlayerPreference::class, 'user_id');
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(Player::class, 'player_preferences', 'user_id', 'player_id')
            ->withPivot(['is_target','value','integrity','quality','notes','rank'])
            ->withTimestamps();
    }

    // FantaTeam dell'utente
    public function fantaTeams(): HasMany
    {
        return $this->hasMany(FantaTeam::class, 'user_id');
    }

    public function upsertPlayerPreference(int $playerId, array $attrs = []): PlayerPreference
    {
        return PlayerPreference::updateOrCreate(
            ['user_id' => $this->id, 'player_id' => $playerId],
            $attrs
        );
    }

    public function removePlayerPreference(int $playerId): void
    {
        PlayerPreference::where('user_id', $this->id)->where('player_id', $playerId)->delete();
    }

    public function getPlayerPreference(int $playerId): ?PlayerPreference
    {
        return PlayerPreference::where('user_id', $this->id)->where('player_id', $playerId)->first();
    }

    public function resolvedPlayers(): Collection
    {
        return $this->players()->get();
    }
}
