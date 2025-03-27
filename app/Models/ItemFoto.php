<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemFoto extends Model
{
    /**
     * Nome da tabela associada ao model.
     *
     * @var string
     */
    protected $table = 'item_fotos';

    /**
     * Atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'caminho',
        'ordem',
        'descricao_foto', // opcional: caso queira adicionar descrição individual
        'is_principal' // opcional: para marcar foto principal
    ];

    /**
     * Atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'ordem' => 'integer',
        'is_principal' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Valores padrão para os atributos.
     *
     * @var array
     */
    protected $attributes = [
        'ordem' => 0,
        'is_principal' => false
    ];

    /**
     * Relacionamento com o Item.
     *
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Acessor para obter a URL completa da foto.
     *
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->caminho);
    }

    /**
     * Escopo para fotos principais.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePrincipal($query)
    {
        return $query->where('is_principal', true);
    }

    /**
     * Escopo ordenado por posição.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdenado($query)
    {
        return $query->orderBy('ordem');
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        // Garante que só tenha uma foto principal por item
        static::saving(function ($foto) {
            if ($foto->is_principal) {
                self::where('item_id', $foto->item_id)
                    ->where('id', '!=', $foto->id)
                    ->update(['is_principal' => false]);
            }
        });
    }
}