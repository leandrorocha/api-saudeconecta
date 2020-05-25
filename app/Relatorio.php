<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Relatorio extends Model
{
    protected $table = 'relatorios';

    protected $fillable = [
        'user_id',
        'cpf',
        'teve_febre',
        'teve_tosse',
        'teve_contato',
        'teve_dor_garganta',
        'teve_dificuldade_respirar',
        'anotacao',
        'logradouro',
        'latitude',
        'longitude',
        'tipo_caso'
    ];

}
