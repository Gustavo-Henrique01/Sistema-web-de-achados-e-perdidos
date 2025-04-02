@extends('admin.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header com Título e Busca -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-users me-2"></i>Usuários Cadastrados</h2>
        <div class="d-flex">
            <form method="GET" action="{{ route('admin.listar-usuarios') }}" class="d-flex" id="searchForm">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Buscar usuário..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Dashboard de Estatísticas -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100 bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total de Usuários</h6>
                            <h2 class="mt-2 mb-0">{{ $users->count() }}</h2>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100 bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Usuários Ativos</h6>
                            <h2 class="mt-2 mb-0">{{ $users->where('ativo', true)->count() }}</h2>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-user-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100 bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Usuários Inativos</h6>
                            <h2 class="mt-2 mb-0">{{ $users->where('ativo', false)->count() }}</h2>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-user-slash fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100 bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Itens Cadastrados</h6>
                            <h2 class="mt-2 mb-0">{{ App\Models\Item::count() }}</h2>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-boxes fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Usuários -->
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Lista de Usuários</h5>
            <span class="badge bg-primary">{{ $users->count() }} usuários encontrados</span>
        </div>
        
        <div class="card-body">
            @if ($users->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Nenhum usuário encontrado.</p>
                </div>
            @else
                <div class="row">
                    @foreach ($users as $user)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm border-0 user-card">
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <div class="user-avatar mx-auto mb-3">
                        @if ($user->foto)
                                                <img src="{{ asset('storage/'.$user->foto) }}" alt="Foto do usuário">
                        @else
                                                <div class="avatar-placeholder">
                                                    <i class="fas fa-user"></i>
                                                </div>
                        @endif
                                        </div>
                                        <h5 class="card-title mb-1">{{ $user->name }}</h5>
                                        <p class="text-muted mb-2">{{ $user->email }}</p>
                                        <span class="badge bg-{{ $user->ativo ? 'success' : 'danger' }}">
                                            {{ $user->ativo ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </div>
                                    
                                    <div class="user-info">
                                        <div class="info-item">
                                            <i class="fas fa-phone me-2 text-muted"></i>
                                            {{ $user->telefone ?? 'Não informado' }}
                                        </div>
                                        <div class="info-item">
                                            <i class="fas fa-id-card me-2 text-muted"></i>
                                            {{ $user->cpf }}
                                        </div>
                                        <div class="info-item">
                                            <i class="fas fa-calendar-alt me-2 text-muted"></i>
                                            Cadastro: {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                    
                                    <div class="user-stats mt-3">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <h6>{{ $user->itens->count() }}</h6>
                                                <small class="text-muted">Itens</small>
                                            </div>
                                            <div class="col-6">
                                                <h6>{{ $user->itens->where('status', 'aprovado')->count() }}</h6>
                                                <small class="text-muted">Aprovados</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-footer bg-light">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('admin.perfilUser', $user->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i> Ver Perfil
                                        </a>
                        <form action="{{ route('admin.deletar-usuario', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">
                                                <i class="fas fa-trash-alt me-1"></i> Excluir
                                            </button>
                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
            @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .stat-icon {
        opacity: 0.7;
    }
    .user-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto;
        border: 3px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .avatar-placeholder {
        width: 100%;
        height: 100%;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #adb5bd;
    }
    .user-card {
        transition: transform 0.2s;
    }
    .user-card:hover {
        transform: translateY(-5px);
    }
    .info-item {
        padding: 0.5rem 0;
        border-bottom: 1px solid #f8f9fa;
    }
    .info-item:last-child {
        border-bottom: none;
    }
    .user-stats h6 {
        margin-bottom: 0;
        font-size: 1.2rem;
        color: #0d6efd;
    }
    .badge {
        padding: 0.5em 0.8em;
        font-weight: 500;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Submissão automática do formulário de busca
    const searchInput = document.querySelector('input[name="search"]');
    let timeout = null;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            document.getElementById('searchForm').submit();
        }, 500);
    });
});
</script>
@endsection