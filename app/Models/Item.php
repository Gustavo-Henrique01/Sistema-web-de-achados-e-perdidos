<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    // Defina o nome da tabela (se necessário)
    protected $table = 'itens';

    // Campos que podem ser atribuídos em massa
    protected $fillable = [
        
        'descricao',
        'id_categoria',
        'user_id',
        'status',
        'aprovado',
        'aprovado_por_id',
        'reprovado_por_id',
        'excluido_por_id',
        'aprovado_em',
        'reprovado_em',
        'excluido_em',
        'id_localizacao',
        'tipo',
        'data_perdido',
        'data_encontrado',
        'parceiro_id'
    ];

    protected $casts = [
        'aprovado' => 'boolean',
        'aprovado_em' => 'datetime',
        'reprovado_em' => 'datetime',
        'excluido_em' => 'datetime',
        'status' => 'string'
    ];

    // Status possíveis para o item
    const STATUS_PENDENTE = 'pendente';
    const STATUS_APROVADO = 'aprovado';
    const STATUS_REPROVADO = 'reprovado';
    const STATUS_EM_TRANSFERENCIA = 'em_transferencia';
    const STATUS_EM_ESTABELECIMENTO = 'em_estabelecimento';
    const STATUS_DEVOLVIDO = 'devolvido';

    // No model Item.php
        public function fotos()
        {
            return $this->hasMany(ItemFoto::class);
        }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria','id');
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

    // Relacionamentos
    public function aprovadoPor()
    {
        return $this->belongsTo(User::class, 'aprovado_por_id');
    }

    public function reprovadoPor()
    {
        return $this->belongsTo(User::class, 'reprovado_por_id');
    }

    public function excluidoPor()
    {
        return $this->belongsTo(User::class, 'excluido_por_id');
    }

    // Relacionamento com transferências
    public function transferencias()
    {
        return $this->hasMany(ItemTransferencia::class);
    }

    // Campos que devem ser ocultados nas respostas JSON
    protected $hidden = [
        // Se necessário, adicione campos sensíveis aqui
    ];

    // Comportamentos adicionais, como ao salvar, pode usar o método booted
    protected static function booted()
    {
    }
}
