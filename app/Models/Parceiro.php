<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Parceiro extends Model
{
    use HasFactory;

    protected $table = 'parceiros';
    
    protected $fillable = [
        'user_id',
        'id_localizacao',
        'nome_estabelecimento',
        'descricao',
        'horario_funcionamento',
        'telefone_comercial',
        'logo',
        'tipo_parceiro',
        'data_inicio_parceria',
        'status',
        'ativo',
        'motivo_reprovacao',
        'motivo_inativacao',
        'aprovado_por_id',
        'data_aprovacao',
        'cnpj',
    ];
    
    protected $casts = [
        'data_inicio_parceria' => 'date',
        'ativo' => 'boolean',
        'data_aprovacao' => 'datetime',
        'data_reprovacao' => 'datetime'
    ];
    
    // Status possíveis
    const STATUS_PENDENTE = 'pendente';
    const STATUS_APROVADO = 'aprovado';
    const STATUS_REPROVADO = 'reprovado';
    
    /**
     * Obtém o usuário associado ao parceiro.
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Obtém a localização do parceiro.
     */
    public function localizacao()
    {
        return $this->belongsTo(Localizacao::class, 'id_localizacao');
    }
    
    /**
     * Obtém os itens associados a este parceiro.
     */
    public function itens()
    {
        return $this->hasMany(Item::class, 'parceiro_id');
    }
    
    /**
     * Obtém os itens que foram transferidos para este parceiro.
     */
    public function transferencias()
    {
        return $this->hasMany(ItemTransferencia::class, 'parceiro_id');
    }
    
    /**
     * Obtém o administrador que aprovou o parceiro.
     */
    public function aprovadoPor()
    {
        return $this->belongsTo(User::class, 'aprovado_por_id');
    }
    
    /**
     * Verifica se o parceiro é um ponto de coleta.
     */
    public function isPontoColeta()
    {
        return $this->tipo_parceiro === 'ponto_coleta' || $this->tipo_parceiro === 'ambos';
    }
    
    /**
     * Verifica se o parceiro é um local de evento.
     */
    public function isEvento()
    {
        return $this->tipo_parceiro === 'evento' || $this->tipo_parceiro === 'ambos';
    }
    
    /**
     * Verifica se o parceiro está pendente de aprovação.
     */
    public function isPendente()
    {
        return $this->status === self::STATUS_PENDENTE;
    }
    
    /**
     * Verifica se o parceiro foi aprovado.
     */
    public function isAprovado()
    {
        return $this->status === self::STATUS_APROVADO;
    }
    
    /**
     * Verifica se o parceiro foi reprovado.
     */
    public function isReprovado()
    {
        return $this->status === self::STATUS_REPROVADO;
    }
    
    /**
     * Escopo para filtrar apenas parceiros ativos.
     */
    public function scopeAtivo($query)
    {
        return $query->where('ativo', true);
    }
    
    /**
     * Escopo para filtrar por tipo de parceiro.
     */
    public function scopeTipo($query, $tipo)
    {
        if ($tipo === 'ponto_coleta' || $tipo === 'evento') {
            return $query->where('tipo_parceiro', $tipo)->orWhere('tipo_parceiro', 'ambos');
        }
        
        return $query->where('tipo_parceiro', $tipo);
    }
    
    /**
     * Escopo para filtrar por status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    
    /**
     * Escopo para filtrar parceiros pendentes.
     */
    public function scopePendente($query)
    {
        return $query->where('status', self::STATUS_PENDENTE);
    }
    
    /**
     * Escopo para filtrar parceiros aprovados.
     */
    public function scopeAprovado($query)
    {
        return $query->where('status', self::STATUS_APROVADO);
    }
}