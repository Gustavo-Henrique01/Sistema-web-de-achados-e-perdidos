<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    // Defina o nome da tabela (se necessário)
    protected $table = 'itens';

    // Campos que podem ser atribuídos em massa
    protected $fillable = [
        'categoria',
        'foto',
        'descricao',
        'data_registro',
        'status',
        'id_usuario',
        'id_endereco',
    ];


    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    
    public function endereco()
    {
        return $this->belongsTo(Endereco::class, 'id_endereco');
    }

    // Campos que devem ser ocultados nas respostas JSON
    protected $hidden = [
        // Se necessário, adicione campos sensíveis aqui
    ];

    // Comportamentos adicionais, como ao salvar, pode usar o método booted
    protected static function booted()
    {
        // Adicionar comportamentos personalizados ao salvar ou excluir o item
    }
}
