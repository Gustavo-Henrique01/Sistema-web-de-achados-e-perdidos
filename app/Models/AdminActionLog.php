<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminActionLog extends Model
{
    protected $fillable = [
        'admin_id',
        'item_id',
        'acao',
        'justificativa',
        'status_anterior',
        'status_novo'
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
} 