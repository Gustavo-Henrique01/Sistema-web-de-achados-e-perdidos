<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    // Defina o nome da tabela (se necessário)
    protected $table = 'itens';

    // Campos que podem ser atribuídos em massa
    protected $fillable = [
        'id_categoria',
        'foto',
        'descricao',
        'data_perdido',
        'data_encontrado',
        'status',
        'user_id',
        'id_localizacao',
        'aprovado',
        'tipo',
        'aprovado_em',
        'parceiro_id'
    ];


    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    // Relacionamento com a tabela usuarios
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relacionamento com a tabela localizacoes
    public function localizacao()
    {
        return $this->belongsTo(Localizacao::class, 'id_localizacao');
    }

    // Relacionamento com a tabela parceiros
    public function parceiro()
    {
        return $this->belongsTo(Parceiro::class, 'parceiro_id');
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
