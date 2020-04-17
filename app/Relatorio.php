<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Relatorio extends Model
{
    protected $table = 'relatorios';

    protected $fillable = [
        'user_id',
        'teve_febre',
        'teve_tosse',
        'teve_contato',
        'teve_dificuldade_respirar',
        'viajou',
        'local',
        'anotacao',
        'latitude',
        'longitude',
        'tipo_caso'
    ];

}
