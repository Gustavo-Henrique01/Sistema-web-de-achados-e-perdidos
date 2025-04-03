@extends('admin.dashboard')

@section('content')
<!-- Meta tag CSRF para requisições AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid p-0" style="min-height: 100vh;">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <a href="{{ route('admin.listar-usuarios') }}" class="btn btn-outline-secondary btn-sm me-2">
                <i class="fas fa-arrow-left me-1"></i> Voltar
            </a>
            <h2 class="mb-0"><i class="fas fa-user-circle me-2"></i>Perfil do Usuário</h2>
        </div>
        <div>
            <button class="btn btn-warning btn-sm" onclick="toggleUserStatus({{ $user->id }}, {{ $user->ativo ? 'false' : 'true' }})">
                <i class="fas fa-{{ $user->ativo ? 'ban' : 'check' }} me-1"></i>
                {{ $user->ativo ? 'Desativar' : 'Ativar' }} Usuário
            </button>
        </div>
    </div>

    <!-- Informações do Usuário -->
    <div class="row g-3 mb-4">
        <!-- Card de Perfil -->
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="user-avatar mx-auto mb-3">
                        @if ($user->foto)
                            <img src="{{ asset('storage/'.$user->foto) }}" alt="Foto do usuário" class="img-fluid">
                        @else
                            <div class="avatar-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </div>
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-2">{{ $user->email }}</p>
                    <span class="badge bg-{{ $user->ativo ? 'success' : 'danger' }} mb-3">
                        {{ $user->ativo ? 'Ativo' : 'Inativo' }}
                    </span>
                    
                    <div class="user-info text-start">
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
                </div>
            </div>
        </div>

        <!-- Estatísticas do Usuário -->
        <div class="col-12 col-md-8">
            <div class="row g-3">
                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Total</h6>
                                    <h2 class="mt-2 mb-0">{{ $itens->count() }}</h2>
                                </div>
                                <div class="stat-icon">
                                    <i class="fas fa-boxes"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Aprovados</h6>
                                    <h2 class="mt-2 mb-0">{{ $itens->where('status', 'aprovado')->count() }}</h2>
                                </div>
                                <div class="stat-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Pendentes</h6>
                                    <h2 class="mt-2 mb-0">{{ $itens->where('status', 'pendente')->count() }}</h2>
                                </div>
                                <div class="stat-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Reprovados</h6>
                                    <h2 class="mt-2 mb-0">{{ $itens->where('status', 'reprovado')->count() }}</h2>
                                </div>
                                <div class="stat-icon">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Itens -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h5 class="mb-2 mb-md-0"><i class="fas fa-list me-2"></i>Itens Cadastrados</h5>
                <div class="btn-group btn-group-sm flex-wrap mt-2 mt-md-0 w-100 w-md-auto">
                    <button type="button" class="btn btn-outline-secondary active" data-filter="todos">
                        Todos <span class="badge bg-secondary">{{ $itens->count() }}</span>
                    </button>
                    <button type="button" class="btn btn-outline-success" data-filter="aprovado">
                        Aprovados <span class="badge bg-success">{{ $itens->where('status', 'aprovado')->count() }}</span>
                    </button>
                    <button type="button" class="btn btn-outline-warning" data-filter="pendente">
                        Pendentes <span class="badge bg-warning">{{ $itens->where('status', 'pendente')->count() }}</span>
                    </button>
                    <button type="button" class="btn btn-outline-danger" data-filter="reprovado">
                        Reprovados <span class="badge bg-danger">{{ $itens->where('status', 'reprovado')->count() }}</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body ">
            @if ($itens->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Nenhum item cadastrado por este usuário.</p>
                </div>
            @else
                <div class="row g-3">
                    @foreach ($itens as $item)
                        <div class="col-12 col-sm-6 col-lg-4 col-xl-3 item-card" data-status="{{ $item->status }}">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="position-relative">
                                    @if ($item->fotos && $item->fotos->count() > 0)
                                        <img src="{{ asset('storage/'.$item->fotos->first()->caminho) }}" 
                                             class="card-img-top img-fluid" alt="Foto do item"
                                             style="height: 180px; object-fit: cover;">
                                    @else
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                             style="height: 180px;">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-{{ $item->status === 'aprovado' ? 'success' : ($item->status === 'pendente' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <h5 class="card-title text-truncate">{{ $item->categoria->nome_categoria }}</h5>
                                    <p class="card-text text-truncate-2" style="-webkit-line-clamp: 2; display: -webkit-box; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $item->descricao }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            {{ \Carbon\Carbon::parse($item->data_registro)->format('d/m/Y') }}
                                        </small>
                                        <small class="text-muted">
                                            <i class="fas fa-{{ $item->tipo === 'achado' ? 'hand-holding' : 'search' }} me-1"></i>
                                            {{ ucfirst($item->tipo) }}
                                        </small>
                                    </div>
                                </div>
                                
                                <div class="card-footer bg-light p-2">
                                    <div class="d-flex flex-column flex-sm-row gap-1">
                                        <button type="button" class="btn btn-sm btn-primary flex-grow-1" 
                                                onclick="viewItemDetails({{ $item->id }})">
                                            <i class="fas fa-eye me-1"></i> Detalhes
                                        </button>
                                        @if($item->status === 'pendente')
                                            <div class="d-flex gap-1 mt-1 mt-sm-0">
                                                <form action="{{ route('admin.itens-aprovar', $item->id) }}" method="POST" class="flex-fill">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success w-100">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.itens-rejeitar', $item->id) }}" method="POST" class="flex-fill">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger w-100">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
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

<!-- Modal de Detalhes do Item -->
<div class="modal fade" id="itemDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalhes do Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Conteúdo será carregado via AJAX -->
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos do container */
    .container-fluid {
        width: 100%;
        min-height: 100vh;
        padding: 0;
        margin: 0;
    }
    
    /* Make main content area expand */
    .content-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
        width: 100%;
        padding: 1rem;
    }
    
    /* Card should expand to fill available space */
    .card.shadow-sm {
        flex: 1;
        display: flex;
        flex-direction: column;
        margin-bottom: 1rem;
    }
    
    /* Card body should expand */
    .card-body {
        flex: 1 1 auto;
        min-height: 0;
    }
    
    /* Estilos do avatar */
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
    
    /* Estilos dos cards de estatística */
    .stat-icon i {
        font-size: 1.5rem;
        opacity: 0.7;
    }
    
    /* Estilos dos botões de filtro */
    .btn-group.flex-wrap {
        gap: 0.25rem;
    }
    
    .btn-group .btn {
        flex: 1 0 auto;
        min-width: 120px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Estilos dos cards de itens */
    .item-card .card {
        transition: transform 0.2s;
    }
    
    .item-card:hover .card {
        transform: translateY(-5px);
    }

    /* Ajustes para remover espaços indesejados */
    .row {
        margin: 0;
    }

    [class*="col-"] {
        padding: 0.5rem;
    }

    .card {
        margin: 0;
    }

    .card-header {
        padding: 1rem;
    }

    .container-fluid > .row {
        width: 100%;
        margin: 0;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtro de itens por status
    const filterButtons = document.querySelectorAll('[data-filter]');
    const itemCards = document.querySelectorAll('.item-card');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Atualiza botões ativos
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filtra os cards
            itemCards.forEach(card => {
                if (filter === 'todos' || card.dataset.status === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});

function viewItemDetails(itemId) {
    const modal = new bootstrap.Modal(document.getElementById('itemDetailsModal'));
    const modalBody = document.querySelector('#itemDetailsModal .modal-body');
    
    // Mostra loading
    modalBody.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';
    modal.show();
    
    // Carrega detalhes via AJAX
    fetch(`/admin/itens/${itemId}/detalhes`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro ao carregar detalhes do item');
            }
            return response.text();
        })
        .then(html => {
            modalBody.innerHTML = html;
        })
        .catch(error => {
            modalBody.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-circle fa-2x text-danger mb-3"></i>
                    <p class="text-danger mb-0">Erro ao carregar detalhes do item</p>
                </div>
            `;
        });
}

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
</script>

@endsection