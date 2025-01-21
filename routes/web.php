<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ItemController;






Route::post('/usuarios', [UsuarioController::class, 'criarUsuario'])->name("criar-usuario");
Route::get('/', [UsuarioController::class, 'index']);
Route::post('/item', [ItemController::class, 'registrarItem'])->name("registrar-item");
