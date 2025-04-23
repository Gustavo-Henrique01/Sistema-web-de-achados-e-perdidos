@extends('admin.dashboard')

@section('content')
<div class="container-fluid">
    <!-- Header com Título e Busca -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-users me-2"></i>Usuários Cadastrados</h1>
        <div class="d-flex flex-column flex-md-row gap-2 mt-3 mt-md-0">
            <form method="GET" action="{{ route('admin.listar-usuarios') }}" class="d-flex mb-2 mb-md-0" id="searchForm">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Buscar usuário..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-primary active" data-filter="todos">
                    Todos <span class="badge bg-primary">{{ $users->count() }}</span>
                </button>
                <button type="button" class="btn btn-outline-success" data-filter="ativo">
                    Ativos <span class="badge bg-success">{{ $users->where('ativo', true)->count() }}</span>
                </button>
                <button type="button" class="btn btn-outline-danger" data-filter="inativo">
                    Inativos <span class="badge bg-danger">{{ $users->where('ativo', false)->count() }}</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Dashboard de Estatísticas -->
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card border-start border-4 border-primary shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-primary fw-bold text-uppercase">Total de Usuários</h6>
                            <h2 class="mt-2 mb-0">{{ $users->count() }}</h2>
                        </div>
                        <div class="stat-icon bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-start border-4 border-success shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-success fw-bold text-uppercase">Usuários Ativos</h6>
                            <h2 class="mt-2 mb-0">{{ $users->where('ativo', true)->count() }}</h2>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-user-check fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-start border-4 border-warning shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-warning fw-bold text-uppercase">Usuários Inativos</h6>
                            <h2 class="mt-2 mb-0">{{ $users->where('ativo', false)->count() }}</h2>
                        </div>
                        <div class="stat-icon bg-warning bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-user-slash fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-start border-4 border-info shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-info fw-bold text-uppercase">Itens Cadastrados</h6>
                            <h2 class="mt-2 mb-0">{{ App\Models\Item::count() }}</h2>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-boxes fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Usuários -->
    <div class="card shadow-sm border-0">
        <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary fw-bold"><i class="fas fa-list me-2"></i>Lista de Usuários</h5>
            <span class="badge bg-primary rounded-pill">{{ $users->count() }} usuários encontrados</span>
        </div>
        
        <div class="card-body">
            @if ($users->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Nenhum usuário encontrado.</p>
                </div>
            @else
                <div class="row" id="users-grid">
                    @foreach ($users as $user)
                        <div class="col-md-4 mb-4 user-card" data-status="{{ $user->ativo ? 'ativo' : 'inativo' }}">
                                <div class="card h-100 shadow-sm border-0 position-relative overflow-hidden">
                                    <div class="position-absolute top-0 start-0 w-100 bg-{{ $user->ativo ? 'success' : 'danger' }}" style="height: 5px;"></div>
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
                                            <h5 class="card-title mb-1 fw-bold">{{ $user->name }}</h5>
                                            <p class="text-muted mb-2">{{ $user->email }}</p>
                                            <span class="badge bg-{{ $user->ativo ? 'success' : 'danger' }} rounded-pill mb-3">
                                                {{ $user->ativo ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </div>
                                    
                                    <div class="user-info">
                                        <div class="info-item">
                                            <i class="fas fa-phone me-2 text-primary"></i>
                                            <span class="text-dark">{{ $user->telefone ?? 'Não informado' }}</span>
                                        </div>
                                        <div class="info-item">
                                            <i class="fas fa-id-card me-2 text-primary"></i>
                                            <span class="text-dark">{{ $user->cpf }}</span>
                                        </div>
                                        <div class="info-item">
                                            <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                            <span class="text-dark">Cadastro: {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="user-stats mt-3">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <h6>{{ $user->itens->count() }}</h6>
                                                <small class="text-dark fw-bold">Itens</small>
                                            </div>
                                            <div class="col-6">
                                                <h6>{{ $user->itens->where('status', 'aprovado')->count() }}</h6>
                                                <small class="text-dark fw-bold">Aprovados</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-footer bg-light">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('admin.perfilUser', $user->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i> Ver Perfil
                                        </a>
                                        <button onclick="toggleUserStatus({{ $user->id }}, {{ $user->ativo ? 'false' : 'true' }})" 
                                                class="btn btn-{{ $user->ativo ? 'warning' : 'success' }} btn-sm">
                                            <i class="fas fa-{{ $user->ativo ? 'ban' : 'check' }} me-1"></i>
                                            {{ $user->ativo ? 'Desativar' : 'Ativar' }}
                                        </button>
                                        <button onclick="deleteUser({{ $user->id }})" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt me-1"></i> Excluir
                                        </button>
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
    /* Estilos para os cards de estatísticas */
    .stat-icon {
        opacity: 0.9;
        transition: all 0.3s ease;
    }
    
    .card:hover .stat-icon {
        transform: scale(1.1);
    }
    
    /* Estilos para o avatar do usuário */
    .user-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto;
        border: 3px solid #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
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
        color: #6c757d;
    }
    
    /* Animação e estilo dos cards de usuário */
    .user-card {
        transition: all 0.3s ease;
    }
    
    .user-card:hover {
        transform: translateY(-8px);
    }
    
    .user-card:hover .user-avatar {
        transform: scale(1.05);
        box-shadow: 0 8px 15px rgba(0,0,0,0.15);
    }
    
    /* Estilos para os itens de informação */
    .info-item {
        padding: 0.5rem 0;
        border-bottom: 1px solid #f0f0f0;
        font-weight: 500;
        transition: background-color 0.2s ease;
    }
    
    .info-item:hover {
        background-color: #f8f9fa;
        border-radius: 4px;
        padding-left: 5px;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    /* Estilos para estatísticas do usuário */
    .user-stats h6 {
        margin-bottom: 0;
        font-size: 1.2rem;
        color: #0d6efd;
        font-weight: 600;
    }
    
    /* Estilos para badges */
    .badge {
        padding: 0.5em 0.8em;
        font-weight: 500;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
        .d-flex.flex-column.flex-md-row {
            gap: 1rem;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterButtons = document.querySelectorAll('[data-filter]');
    const userCards = document.querySelectorAll('.user-card');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter cards
            userCards.forEach(card => {
                if (filter === 'todos' || card.dataset.status === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Search functionality with debounce
    const searchInput = document.querySelector('input[name="search"]');
    let timeout = null;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            document.getElementById('searchForm').submit();
        }, 500);
    });
});

function toggleUserStatus(userId, newStatus) {
    if (confirm(`Tem certeza que deseja ${newStatus ? 'ativar' : 'desativar'} este usuário?`)) {
        fetch(`/admin/usuario/${userId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro ao atualizar status do usuário');
            }
            window.location.reload();
        })
        .catch(error => {
            alert('Erro ao atualizar status do usuário');
        });
    }
}

function deleteUser(userId) {
    if (confirm('Tem certeza que deseja excluir este usuário?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/usuario/${userId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection