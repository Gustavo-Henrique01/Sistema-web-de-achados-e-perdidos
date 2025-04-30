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
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PusherAuthController;


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('form.login');
Route::post('/', [LoginController::class, 'login'])->name('login');;

 Route::post('/logout', [LoginController::class, 'sair'])->name('logout');

// Rotas para redefinição de senha
Route::get('/forgot-password', [LoginController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [LoginController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [LoginController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('password.update');

Route::get('/', [IndexController::class, 'paginaInicial'])->name('paginaInicial');

// Rotas para transferência de itens
Route::middleware(['auth'])->group(function () {
    Route::post('/item/{item}/enviar-para-parceiro', [ItemController::class, 'enviarParaParceiro'])->name('item.enviar-para-parceiro');
    Route::get('/item/{item}/enviar-para-parceiro', [ItemController::class, 'enviarParaParceiroForm'])->name('item.enviar-para-parceiro-form');
    
    // Rota simplificada para devolução de item
    Route::post('/item/{item}/marcar-como-devolvido', [ItemController::class, 'marcarComoDevolvido'])
        ->name('item.marcar-como-devolvido');
    
    // Rota para busca de usuários por email (autocomplete)
    Route::get('/usuarios/search', [UsuarioController::class, 'searchByEmail'])->name('usuarios.search');
    
    // Rotas para notificações
    Route::prefix('notificacoes')->group(function () {
        Route::post('/{id}/marcar-lida', [NotificationController::class, 'markAsRead'])->name('notificacoes.marcar-lida');
        Route::post('/marcar-todas-lidas', [NotificationController::class, 'markAllAsRead'])->name('notificacoes.marcar-todas-lidas');
        Route::get('/contagem', [NotificationController::class, 'getUnreadCount'])->name('notificacoes.contagem');
        Route::get('/recentes', [NotificationController::class, 'getRecentNotifications'])->name('notificacoes.recentes');
        Route::post('/{id}', [NotificationController::class, 'deleteNotification'])->name('notificacoes.excluir');
    });
});

// Rota para exibir o formulário de registro do usuário
Route::get('/usuario', [UsuarioController::class, 'index'])->name('registrar');

// Rota para criar um novo usuário (POST)
Route::post('/usuarios', [UsuarioController::class, 'criarUsuario'])->name("criar-usuario");

// Rota para registrar um novo item (POST)
Route::post('/item', [ItemController::class, 'registroItem'])->name("registrar-item");

// Rota para exibir o formulário de cadastro de item
Route::get('/form-item', [ItemController::class, 'index'])->name("cadastro-item");




Route::get('/itens', [ItemController::class, 'listarItens'])->name('itens.index');
Route::get('/itens/mapa', [ItemController::class, 'mapaItens'])->name('itens.mapa');


Route::get('/mapa', [MapController::class, 'mostrarMapa'])->name('mapa');

Route::prefix('user')->middleware(['auth', 'user'])->group(function () { 
    Route::get('/home', [UsuarioController::class,'home'])->name('usuario.home');
    Route::get('/form-item', [ItemController::class, 'index'])->name("usuario.cadastrar-item");
    Route::post('/item', [ItemController::class, 'registroItem'])->name("registrar-item");

    Route::get('/itens', [ItemController::class, 'listarItens'])->name('listar-todos-itens');
    Route::get('/meus-perfil', [UsuarioController::class, 'perfilUsuario'])->name('perfil-usuario');
    Route::get('/editar/{id}', [UsuarioController::class, 'editarItem'])->name('usuario.editar-item');
    Route::put('/atualizar/{id}', [UsuarioController::class, 'atualizarItem'])->name('usuario.atualizar-item');
    Route::delete('/deletar-item/{id}', [UsuarioController::class, 'excluirItem'])->name('usuario.deletar-item');

    Route::get('/itens/{item}', [ItemController::class, 'show'])->name('itens.show');

    // Rotas para edição de perfil
    Route::get('/edit-profile', [UsuarioController::class, 'editProfile'])->name('usuario.edit-profile');
    Route::put('/update-profile', [UsuarioController::class, 'updateProfile'])->name('usuario.update-profile');
    Route::post('/desativar-conta', [UsuarioController::class, 'desativarConta'])->name('usuario.desativar-conta');
    Route::post('/reativar-conta', [UsuarioController::class, 'reativarConta'])->name('usuario.reativar-conta');

});


Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
// Rota para listar itens pendentes
Route::get('/admin/filter', [AdministradorController::class, 'listarItens'])->name('admin.listar-itens');
Route::get('/admin/itens', [AdministradorController::class, ' listarItensAll'])->name('admin.listar-itens-all');

// Rotas para aprovar ou rejeitar itens
Route::get('/usuarios', [AdministradorController::class, 'listarUsuarios'])->name('admin.listar-usuarios');
Route::delete('/usuario/{id}', [AdministradorController::class, 'excluirUsuario'])->name('admin.deletar-usuario');
Route::post('/usuario/{id}/toggle-status', [AdministradorController::class, 'toggleUserStatus'])->name('admin.toggle-user-status');
Route::delete('/item/{id}', [AdministradorController::class, 'excluirItem'])->name('admin.deletar-item');
Route::get('/usuario/{id}/itens', [AdministradorController::class, 'PerfilUser'])->name('admin.perfilUser');

// Rotas para gestão de administradores
Route::get('/admins', [AdministradorController::class, 'listarAdmins'])->name('admin.listar-admins');
Route::get('/admin/cadastrar', [AdministradorController::class, 'formAdmin'])->name('admin.cadastrar-admin');
Route::post('/admin/cadastrar', [AdministradorController::class, 'cadastrarAdmin'])->name('admin.criar-admin');

// Rotas para listagem e detalhes de itens
Route::get('/itens', [AdministradorController::class, 'listarItens'])->name('admin.listar-itens');
Route::get('/itens/{id}/detalhes', [AdministradorController::class, 'verDetalhesItem'])->name('admin.ver-detalhes-item');

// Rotas para ações em itens
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


// Rotas do perfil do administrador
Route::get('/perfil', [AdministradorController::class, 'perfil'])->name('admin.perfil');
Route::put('/perfil', [AdministradorController::class, 'atualizarPerfil'])->name('admin.atualizar-perfil');
Route::put('/perfil/senha', [AdministradorController::class, 'alterarSenha'])->name('admin.alterar-senha');

// Rota para o log de ações
Route::get('/log-acoes', [AdministradorController::class, 'logAcoes'])->name('admin.log-acoes');

// Rotas para gerenciamento de parceiros
Route::get('/parceiros', [AdministradorController::class, 'listarParceiros'])->name('admin.parceiros.index');
Route::get('/parceiros/{parceiro}', [AdministradorController::class, 'verParceiro'])->name('admin.parceiros.show');
Route::post('/parceiros/{parceiro}/aprovar', [AdministradorController::class, 'aprovarParceiro'])->name('admin.parceiros.aprovar');
Route::post('/parceiros/{parceiro}/reprovar', [AdministradorController::class, 'reprovarParceiro'])->name('admin.parceiros.reprovar');
Route::post('/parceiros/{parceiro}/desativar', [AdministradorController::class, 'desativarParceiro'])->name('admin.parceiros.desativar');
Route::get('/parceiros/{parceiro}/itens', [AdministradorController::class, 'listarItensParceiro'])->name('admin.parceiros.itens');

Route::post('{parceiro}/desativar', [AdministradorController::class, 'desativar'])->name('parceiros.desativar');
Route::delete('{parceiro}', [AdministradorController::class, 'destroy'])->name('admin.parceiros.destroy');


});

// Rotas para parceiros
Route::middleware(['auth'])->prefix('parceiro')->name('parceiro.')->group(function () {
    // Rotas que não precisam do middleware 'parceiro'
    Route::get('/aguardando-aprovacao', [ParceiroController::class, 'aguardandoAprovacao'])
        ->name('aguardando-aprovacao');
    Route::get('/inativo', [ParceiroController::class, 'inativo'])
        ->name('inativo');
 
    
});
Route::get('/editar/{parceiro}', [ParceiroController::class, 'editarCadastro'])
->name('parceiro.editar');

// Rotas que precisam do middleware 'parceiro'
Route::middleware(['auth', 'parceiro'])->prefix('parceiro')->name('parceiro.')->group(function () {
    Route::get('/home', [ParceiroController::class, 'home'])->name('home');
    Route::get('/itens', [ParceiroController::class, 'listarItens'])->name('itens');
    Route::get('/itens/{item}', [ItemController::class, 'showParceiro'])->name('itens.show');
    Route::get('/vincular-item', [ParceiroController::class, 'vincularItemForm'])->name('vincular-item.form');
    Route::get('/transferencias-pendentes', [ParceiroController::class, 'transferenciasPendentes'])->name('transferencias-pendentes');
    Route::get('/perfil', [ParceiroController::class, 'editProfile'])->name('perfil');
    Route::put('/perfil', [ParceiroController::class, 'updateProfile'])->name('update-profile');
    
    // Rotas de transferência de itens
    Route::post('/itens/{item}/confirmar-recebimento', [ParceiroController::class, 'confirmarRecebimento'])
        ->name('itens.confirmar-recebimento');
    Route::post('/itens/{item}/rejeitar', [ParceiroController::class, 'rejeitarRecebimento'])
        ->name('itens.rejeitar');
    Route::post('/itens/{item}/marcar-devolvido', [ParceiroController::class, 'marcarDevolvido'])
        ->name('itens.marcar-devolvido');
    Route::post('/itens/{item}/desvincular', [ParceiroController::class, 'desvincularItem'])
        ->name('desvincular-item');
});

// Rota para página de aguardando aprovação (não precisa de middleware parceiro)
Route::get('/parceiro/aguardando-aprovacao', [ParceiroController::class, 'aguardandoAprovacao'])
    ->middleware(['auth'])
    ->name('parceiro.aguardando-aprovacao');

// Rota para visualizar todos os parceiros no mapa (pública)
Route::get('/parceiros/mapa', [ParceiroController::class, 'mapa'])->name('parceiros.mapa');

// Rota para exibir o formulário de registro do parceiro
Route::get('/parceiro/registro', [ParceiroController::class, 'create'])->name('parceiro.create');
Route::post('/parceiro', [ParceiroController::class, 'store'])->name('parceiro.store');

// Rotas para transferência de itens
Route::middleware(['auth'])->group(function () {
    // Usuário envia item para parceiro
    Route::post('/item/{item}/enviar-para-parceiro', [ItemController::class, 'enviarParaParceiro'])
        ->name('item.enviar-para-parceiro');
});

// Rotas de notificações
Route::middleware(['auth'])->group(function () {
    Route::post('/notificacoes/{id}/marcar-lida', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notificacoes/marcar-todas-lidas', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notificacoes/nao-lidas', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::get('/notificacoes/recentes', [NotificationController::class, 'getRecentNotifications'])->name('notifications.recent');
    Route::delete('/notificacoes/{id}', [NotificationController::class, 'deleteNotification'])->name('notifications.delete');
    Route::post('/pusher/auth', [PusherAuthController::class, 'authenticate'])->name('pusher.auth');
});

// Rotas de Parceiros
