<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // Para suportar autenticação
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable; // Necessário para notificação de usuários (caso seja usado)

    // Se sua tabela no banco de dados for 'usuarios', adicione a propriedade $table
    protected $table = 'usuarios'; // Tabela personalizada

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'senha',
        'foto',
        'cpf',
        'role',
    ];

    // Campos que devem ser escondidos (não expostos)
    protected $hidden = [
        'senha', 
        'remember_token', // Necessário para autenticação de sessão
    ];

    public function getAuthPassword()
    {
        return $this->senha; // A coluna 'senha' será usada no lugar de 'password'
    }

    // Relacionamento: um usuário pode ter muitos itens
    public function itens()
    {
        return $this->hasMany(Item::class, 'id_usuario');
    }

    // Método para verificar se o usuário é um administrador
    public function isAdmin()
    {
        return $this->role === 'administrador';
    }

    // Método para verificar se o usuário é um usuário comum
    public function isUser()
    {
        return $this->role === 'usuario';
    }

    // Sempre hash a senha ao salvar ou atualizar
    protected static function booted()
    {
        static::saving(function ($usuario) {
            if ($usuario->isDirty('senha')) {
                $usuario->senha = bcrypt($usuario->senha); // Criptografa a senha
            }
        });
    }
}
