<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

enum UserRole: string
{
    case ADMIN = 'administrador';
    case USER = 'usuario';
}

class Usuario extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $table = 'usuarios';
    
    protected $fillable = [
        'nome', 'email', 'telefone',  
        'foto', 'cpf', 'role', 'ativo'
    ];

    protected $hidden = [
        'senha', 
        'remember_token',
    ];

    protected $casts = [
        'senha' => 'hashed',
        'ativo' => 'boolean',
        'role' => UserRole::class,
    ];

    public function getAuthPassword()
    {
        return $this->senha;
    }

    public function itens()
    {
        return $this->hasMany(Item::class, 'id_usuario');
    }

    public function isAdmin()
    {
        return $this->role === UserRole::ADMIN;
    }
    

    public function isUser(): bool
    {
        return $this->role === UserRole::USER;
    }
}
