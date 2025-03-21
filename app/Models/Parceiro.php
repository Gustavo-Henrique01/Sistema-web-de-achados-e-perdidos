<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parceiro extends Model
{
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
        'ativo'
    ];
    
    protected $casts = [
        'data_inicio_parceria' => 'date',
        'ativo' => 'boolean'
    ];
    
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
} 