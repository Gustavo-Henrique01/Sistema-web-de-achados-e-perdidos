<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsuarioController;





Route::post('/usuarios', [UsuarioController::class, 'criarUsuario'])->name("criar-usuario");
Route::get('/', [UsuarioController::class, 'index']);

