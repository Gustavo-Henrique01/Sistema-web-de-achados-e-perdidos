<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    
    protected $fillable = [
        'nome', 'email', 'telefone', 'senha', 
        'foto', 'cpf', 'role', 'ativo'
    ];

    protected $hidden = [
        'senha', 
        'remember_token',
    ];

    // Adicione isso para hashing automático:
    protected $casts = [
        'senha' => 'hashed',
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
        return $this->role === 'administrador';
    }

    public function isUser()
    {
        return $this->role === 'usuario';
    }

    // Remova o booted() se usar $casts['senha']
}