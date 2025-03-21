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
    case PARCEIRO = 'parceiro';
}

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $table = 'users';
    
    protected $fillable = [
        'name', 'email', 'telefone',  
        'foto', 'cpf', 'role', 'ativo', 'senha'
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
        return $this->hasMany(Item::class, 'user_id');
    }

    public function parceiro()
    {
        return $this->hasOne(Parceiro::class, 'user_id');
    }

    public function isAdmin()
    {
        return $this->role === UserRole::ADMIN;
    }
    
    public function isUser(): bool
    {
        return $this->role === UserRole::USER;
    }

    public function isParceiro(): bool
    {
        return $this->role === UserRole::PARCEIRO;
    }

    public function hasParceiro(): bool
    {
        return $this->parceiro()->exists();
    }
}
