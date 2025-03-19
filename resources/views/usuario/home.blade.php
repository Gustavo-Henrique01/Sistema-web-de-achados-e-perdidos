@extends('layouts.app')

@section('title', 'Página Inicial')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-2">Bem-vindo, {{ auth()->user()->name }}!</h3>
                        <p class="text-muted mb-0">O que você gostaria de fazer hoje?</p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <img src="{{ asset('storage/'.auth()->user()->foto) }}" alt="Foto de perfil" 
                             class="rounded-circle border shadow-sm" width="60" height="60"
                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=3498db&color=fff&size=60'">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Card de Cadastrar Item -->
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <i class="fas fa-plus-circle fa-3x text-primary"></i>
                </div>
                <h5 class="card-title">Cadastrar Item</h5>
                <p class="card-text text-muted">Registre um novo item perdido ou encontrado.</p>
            </div>
            <div class="card-footer bg-transparent border-0 text-center pb-4">
                <a href="{{ route('usuario.cadastrar-item') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Novo Item
                </a>
            </div>
        </div>
    </div>

    <!-- Card de Meus Itens -->
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <i class="fas fa-clipboard-list fa-3x text-success"></i>
                </div>
                <h5 class="card-title">Meus Itens</h5>
                <p class="card-text text-muted">Visualize e gerencie seus itens cadastrados.</p>
            </div>
            <div class="card-footer bg-transparent border-0 text-center pb-4">
                <a href="{{ route('perfil-usuario') }}" class="btn btn-success">
                    <i class="fas fa-eye me-2"></i>Ver Itens
                </a>
            </div>
        </div>
    </div>

    <!-- Card de Explorar Mapa -->
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <i class="fas fa-map-marked-alt fa-3x text-warning"></i>
                </div>
                <h5 class="card-title">Mapa Interativo</h5>
                <p class="card-text text-muted">Visualize os itens no mapa da cidade.</p>
            </div>
            <div class="card-footer bg-transparent border-0 text-center pb-4">
                <a href="{{ route('mapa') }}" class="btn btn-warning text-white">
                    <i class="fas fa-map-marker-alt me-2"></i>Ver Mapa
                </a>
            </div>
        </div>
    </div>

    <!-- Card de Todos os Itens -->
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <i class="fas fa-search fa-3x text-info"></i>
                </div>
                <h5 class="card-title">Pesquisar Itens</h5>
                <p class="card-text text-muted">Veja todos os itens cadastrados e encontre o seu.</p>
            </div>
            <div class="card-footer bg-transparent border-0 text-center pb-4">
                <a href="{{ route('listar-todos-itens') }}" class="btn btn-info text-white">
                    <i class="fas fa-search me-2"></i>Ver Todos
                </a>
            </div>
        </div>
    </div>

    <!-- Card de Perfil -->
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <i class="fas fa-user-circle fa-3x text-secondary"></i>
                </div>
                <h5 class="card-title">Meu Perfil</h5>
                <p class="card-text text-muted">Visualize e edite suas informações pessoais.</p>
            </div>
            <div class="card-footer bg-transparent border-0 text-center pb-4">
                <a href="{{ route('perfil-usuario') }}" class="btn btn-secondary">
                    <i class="fas fa-user me-2"></i>Ver Perfil
                </a>
            </div>
        </div>
    </div>
</div>
@endsection