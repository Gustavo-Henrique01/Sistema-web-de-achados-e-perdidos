<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;


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




