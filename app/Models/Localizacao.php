<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Localizacao extends Model
{


    // Nome da tabela (opcional, pois o Laravel usa o nome do model no plural por padrão)
    protected $table = 'localizacoes';

    // Campos que podem ser preenchidos em massa (mass assignment)
    protected $fillable = [
        'nome_local',
        'endereco',
        'latitude',
        'longitude',
        'referencia'
    ];

    // Relacionamento com a tabela itens
    public function itens()
    {
        return $this->hasMany(Item::class, 'id_localizacao');
    }
}


