<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ParceiroController;


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('form.login');
Route::post('/', [LoginController::class, 'login'])->name('login');;

 Route::post('/logout', [LoginController::class, 'sair'])->name('logout');

 Route::get('/', [IndexController::class, 'paginaInicial'])->name('paginaInicial');
    




// Rota para exibir o formulário de registro do usuário
Route::get('/usuario', [UsuarioController::class, 'index'])->name('registrar');

// Rota para criar um novo usuário (POST)
Route::post('/usuarios', [UsuarioController::class, 'criarUsuario'])->name("criar-usuario");

// Rota para registrar um novo item (POST)
Route::post('/item', [ItemController::class, 'registroItem'])->name("registrar-item");

// Rota para exibir o formulário de cadastro de item
Route::get('/form-item', [ItemController::class, 'index'])->name("cadastro-item");




Route::get('/itens', [ItemController::class, 'listarItens'])->name('itens.index');
Route::get('/itens/{item}', [ItemController::class, 'show'])->name('itens.show');


Route::get('/mapa', [MapController::class, 'mostrarMapa'])->name('mapa');

Route::prefix('user')->middleware(['auth', 'user'])->group(function () { 
    Route::get('/home', [UsuarioController::class,'home'])->name('usuario.home');
    Route::get('/form-item', [ItemController::class, 'index'])->name("usuario.cadastrar-item");
    Route::post('/item', [ItemController::class, 'registroItem'])->name("registrar-item");

    Route::get('/itens', [ItemController::class, 'listarItens'])->name('listar-todos-itens');
    Route::get('/meus-perfil', [ItemController::class, 'listarItensUsuario'])->name('perfil-usuario');
    Route::get('/editar/{id}', [UsuarioController::class, 'editarItem'])->name('usuario.editar-item');
    Route::put('/atualizar/{id}', [UsuarioController::class, 'atualizarItem'])->name('usuario.atualizar-item');
    Route::delete('/deletar-item/{id}', [UsuarioController::class, 'excluirItem'])->name('usuario.deletar-item');

});


Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
// Rota para listar itens pendentes
Route::get('/admin/filter', [AdministradorController::class, 'listarItens'])->name('admin.listar-itens');
Route::get('/admin/itens', [AdministradorController::class, ' listarItensAll'])->name('admin.listar-itens-all');
Route::get('/admin/itens/{id}/detalhes', [AdministradorController::class, 'getItemDetails'])->name('admin.item-detalhes');

// Rotas para aprovar ou rejeitar itens
Route::get('/usuarios', [AdministradorController::class, 'listarUsuarios'])->name('admin.listar-usuarios');
Route::delete('/usuario/{id}', [AdministradorController::class, 'excluirUsuario'])->name('admin.deletar-usuario');
Route::delete('/item/{id}', [AdministradorController::class, 'excluirItem'])->name('admin.deletar-item');
Route::get('/usuario/{id}/itens', [AdministradorController::class, 'PerfilUser'])->name('admin.perfilUser');

Route::post('/admin/itens/{id}/aprovar', [AdministradorController::class, 'aprovarItem'])->name('admin.itens-aprovar');
Route::post('/admin/itens/{id}/rejeitar', [AdministradorController::class, 'rejeitarItem'])->name('admin.itens-rejeitar');

Route::delete('/admin/item/{id}/delete', [AdministradorController::class, 'removerItem'])->name('admin.DeletarItem');
Route::get('/', [AdministradorController::class, 'pageAdm'])->name('admin.principal');

Route::post('/save-categoria', [AdministradorController::class, 'cadastrarCategoria'])->name('registrar-categoria');
Route::get('form-categoria', [AdministradorController::class, 'formCategoria'])->name("cadastro-categoria");
Route::get ('/lisstar-categorias', [AdministradorController::class, 'listarCategorias'])->name('listar-categorias');
Route::get('/editar-categoria/{id}', [AdministradorController::class, 'editarCategoria'])->name('editar-categoria');
Route::put('/atualizar-categoria/{id}', [AdministradorController::class, 'atualizarCategoria'])->name('atualizar-categoria');
Route::delete('/deletar-categoria/{id}', [AdministradorController::class, 'excluirCategoria'])->name('categorias.destroy');

// Rotas para gestão de parceiros
Route::get('/parceiros', [ParceiroController::class, 'index'])->name('admin.parceiros.index');
Route::get('/parceiros/create', [ParceiroController::class, 'create'])->name('admin.parceiros.create');
Route::post('/parceiros', [ParceiroController::class, 'store'])->name('admin.parceiros.store');
Route::get('/parceiros/{parceiro}', [ParceiroController::class, 'show'])->name('admin.parceiros.show');
Route::get('/parceiros/{parceiro}/edit', [ParceiroController::class, 'edit'])->name('admin.parceiros.edit');
Route::put('/parceiros/{parceiro}', [ParceiroController::class, 'update'])->name('admin.parceiros.update');
Route::delete('/parceiros/{parceiro}', [ParceiroController::class, 'destroy'])->name('admin.parceiros.destroy');
Route::get('/parceiros/{parceiro}/itens', [ParceiroController::class, 'listarItens'])->name('admin.parceiros.itens');
});

// Rotas para parceiros
Route::prefix('parceiro')->middleware(['auth', 'parceiro'])->group(function () {
    Route::get('/home', [ParceiroController::class, 'home'])->name('parceiro.home');
    Route::get('/itens', function() {
        $parceiro = auth()->user()->parceiro;
        return app()->call([ParceiroController::class, 'listarItens'], ['parceiro' => $parceiro]);
    })->name('parceiro.itens');
    Route::get('/vincular-item', [ParceiroController::class, 'vincularItemForm'])->name('parceiro.vincular-item.form');
    Route::post('/vincular-item', [ParceiroController::class, 'vincularItem'])->name('parceiro.vincular-item');
    Route::post('/desvincular-item/{item}', [ParceiroController::class, 'desvincularItem'])->name('parceiro.desvincular-item');
});

// Rota para visualizar todos os parceiros no mapa (pública)
Route::get('/parceiros/mapa', [ParceiroController::class, 'mapa'])->name('parceiros.mapa');






