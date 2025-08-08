<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'name',
        'team',
        'position',
        'age',
        'nationality',
        'quotation',
        'users_valuation',
        'injury_status'
    ];

    protected $casts = [
        'age' => 'int',
        'quotation' => 'int',
        'users_valuation' => 'float',
        'injury_status' => 'float',
    ];


    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function stats()
    {
        return $this->hasMany(Stat::class);
    }

}
