<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
     protected $fillable = [
        'nome',
        'email',
        'telefone',
        'senha', 
        'foto',
        'cpf',
        'role',
    ];

  
    protected $hidden = [
        'senha', 
    ];

    // Método para verificar se o usuário é administrador
    public function isAdmin()
    {
        return $this->role === 'administrador';
    }

    
    public function isUser()
    {
        return $this->role === 'usuario';
    }

    // Sempre hash a senha ao salvar ou atualizar
    protected static function booted()
    {
        static::saving(function ($usuario) {
            if ($usuario->isDirty('senha')) { 
                $usuario->senha = bcrypt($usuario->senha); 
            }
        });
    }
}
