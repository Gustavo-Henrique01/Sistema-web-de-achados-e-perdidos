<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Http\Controllers\AdministradorController;


// Rota para exibir o formulário de registro do usuário
Route::get('/usuario', [UsuarioController::class, 'index']);

// Rota para criar um novo usuário (POST)
Route::post('/usuarios', [UsuarioController::class, 'criarUsuario'])->name("criar-usuario");

// Rota para registrar um novo item (POST)
Route::post('/item', [ItemController::class, 'registroItem'])->name("registrar-item");

// Rota para exibir o formulário de cadastro de item
Route::get('/form-item', [ItemController::class, 'index'])->name("cadastro-item");

// Rota para exibir o formulário de login (GET)
Route::get('/login', [UsuarioController::class, 'showLogin'])->name("login");

// Rota para autenticar o usuário (POST)
Route::post('/entrar', [UsuarioController::class, 'login'])->name("autenticar");

// Rota para a página inicial (GET)
Route::get('/', [UsuarioController::class, 'index']);

Route::get('/itens', [ItemController::class, 'listarItens'])->name('itens.index');

Route::get('/meus-itens-cadastrados', [UsuarioController::class, 'listarItens']);



Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
// Rota para listar itens pendentes
Route::get('/itens/pendentes', [AdministradorController::class, 'listarItensPendentes'])->name('admin.itens.pendentes');
// Rotas para aprovar ou rejeitar itens
Route::get('/usuarios', [AdministradorController::class, 'listarUsuarios'])->name('admin.listar-usuarios');
Route::delete('/usuario/{id}', [AdministradorController::class, 'excluirUsuario'])->name('admin.deletar-usuario');
Route::delete('/item/{id}', [AdministradorController::class, 'excluirItem'])->name('admin.deletar-item');
Route::get('/usuario/{id}/itens', [AdministradorController::class, 'listarItensPorUsuario'])->name('usuario.itens');

Route::post('/admin/itens/{id}/aprovar', [AdministradorController::class, 'aprovarItem'])->name('admin.itens-aprovar');
Route::post('/admin/itens/{id}/rejeitar', [AdministradorController::class, 'rejeitarItem'])->name('admin.itens-rejeitar');

Route::get('/usuario/{id}', [UsuarioController::class, 'showPerfilUsuario'])->name('admin.ver-usuario-perfil');


});






