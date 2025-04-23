<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTransferencia extends Model
{
    use HasFactory;

    protected $table = 'item_transferencias';

    protected $fillable = [
        'item_id',
        'parceiro_id',
        'usuario_id',
        'observacoes',
        'status',
        'data_confirmacao'
    ];

    protected $casts = [
        'data_confirmacao' => 'datetime'
    ];

    /**
     * Obtém o item relacionado à transferência.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Obtém o parceiro relacionado à transferência.
     */
    public function parceiro()
    {
        return $this->belongsTo(Parceiro::class);
    }

    /**
     * Obtém o usuário que iniciou a transferência.
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Verifica se a transferência está pendente.
     */
    public function isPendente()
    {
        return $this->status === 'pendente';
    }

    /**
     * Verifica se a transferência foi confirmada.
     */
    public function isConfirmada()
    {
        return $this->status === 'confirmada';
    }

    /**
     * Verifica se a transferência foi rejeitada.
     */
    public function isRejeitada()
    {
        return $this->status === 'rejeitada';
    }
} 