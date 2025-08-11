<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function stats()
    {
        return $this->hasMany(Stat::class, 'id', 'id');
    }

}
