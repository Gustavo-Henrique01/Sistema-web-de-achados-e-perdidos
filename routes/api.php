<?php

use App\Http\Controllers\UsuarioController;

Route::post('/usuarios', [UsuarioController::class, 'criarUsuario']);


