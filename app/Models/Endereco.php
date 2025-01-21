<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    protected $table = 'enderecos';

    
    protected $fillable = [
        'rua',
        'numero',
        'bairro',
        'referencial',
        'cidade',
        'estado'
    ];

   
    protected $hidden = [];

    // Caso o campo 'created_at' e 'updated_at' não sejam usados ou tenham outro nome
    // Você pode desabilitar as timestamps, caso contrário, o Laravel assume que você está usando elas.
    public $timestamps = true;

    public function itens()
    {
        return $this->hasMany(Item::class, 'id_endereco');
    }
}
