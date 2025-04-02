@extends('admin.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header com Navegação -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.listar-usuarios') }}" class="btn btn-outline-secondary btn-sm me-2">
                <i class="fas fa-arrow-left me-1"></i> Voltar
            </a>
            <h2 class="mb-0"><i class="fas fa-user-circle me-2"></i>Perfil do Usuário</h2>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-warning btn-sm" onclick="toggleUserStatus({{ $user->id }}, {{ $user->ativo ? 'false' : 'true' }})">
                <i class="fas fa-{{ $user->ativo ? 'ban' : 'check' }} me-1"></i>
                {{ $user->ativo ? 'Desativar' : 'Ativar' }} Usuário
            </button>
        </div>
    </div>

    <!-- Informações do Usuário -->
    <div class="row mb-4">
        <!-- Card de Perfil -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="user-avatar mx-auto mb-3">
                        @if ($user->foto)
                            <img src="{{ asset('storage/'.$user->foto) }}" alt="Foto do usuário">
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
        <div class="col-md-8 mb-4">
    <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100 bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Total de Itens</h6>
                                    <h2 class="mt-2 mb-0">{{ $itens->count() }}</h2>
                                </div>
                                <div class="stat-icon">
                                    <i class="fas fa-boxes fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100 bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Itens Aprovados</h6>
                                    <h2 class="mt-2 mb-0">{{ $itens->where('status', 'aprovado')->count() }}</h2>
                                </div>
                                <div class="stat-icon">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100 bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Itens Pendentes</h6>
                                    <h2 class="mt-2 mb-0">{{ $itens->where('status', 'pendente')->count() }}</h2>
                                </div>
                                <div class="stat-icon">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100 bg-danger text-white">
                    <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Itens Reprovados</h6>
                                    <h2 class="mt-2 mb-0">{{ $itens->where('status', 'reprovado')->count() }}</h2>
                                </div>
                                <div class="stat-icon">
                                    <i class="fas fa-times-circle fa-2x"></i>
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
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Itens Cadastrados</h5>
            <div class="d-flex gap-2">
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-secondary active" data-filter="todos">
                        Todos <span class="badge bg-secondary">{{ $itens->count() }}</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-success" data-filter="aprovado">
                        Aprovados <span class="badge bg-success">{{ $itens->where('status', 'aprovado')->count() }}</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-warning" data-filter="pendente">
                        Pendentes <span class="badge bg-warning">{{ $itens->where('status', 'pendente')->count() }}</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" data-filter="reprovado">
                        Reprovados <span class="badge bg-danger">{{ $itens->where('status', 'reprovado')->count() }}</span>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            @if ($itens->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Nenhum item cadastrado por este usuário.</p>
                </div>
            @else
                <div class="row">
                    @foreach ($itens as $item)
                        <div class="col-md-4 mb-4 item-card" data-status="{{ $item->status }}">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="position-relative">
                                    @if ($item->fotos && $item->fotos->count() > 0)
                                        <img src="{{ asset('storage/'.$item->fotos->first()->caminho) }}" 
                                             class="card-img-top" alt="Foto do item"
                                             style="height: 200px; object-fit: cover;">
                                    @else
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                             style="height: 200px;">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-{{ $item->status === 'aprovado' ? 'success' : 
                                                               ($item->status === 'pendente' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <h5 class="card-title">{{ $item->categoria->nome_categoria }}</h5>
                                    <p class="card-text text-truncate">{{ $item->descricao }}</p>
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
                                
                                <div class="card-footer bg-light">
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-primary flex-grow-1" 
                                                onclick="viewItemDetails({{ $item->id }})">
                                            <i class="fas fa-eye me-1"></i> Ver Detalhes
                                        </button>
                                        @if($item->status === 'pendente')
                        <form action="{{ route('admin.itens-aprovar', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i>
                            </button>
                        </form>
                        <form action="{{ route('admin.itens-rejeitar', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i>
                            </button>
                        </form>
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
    .stat-icon {
        opacity: 0.7;
    }
    .user-avatar {
        width: 120px;
        height: 120px;
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
        font-size: 2.5rem;
        color: #adb5bd;
    }
    .info-item {
        padding: 0.5rem 0;
        border-bottom: 1px solid #f8f9fa;
    }
    .info-item:last-child {
        border-bottom: none;
    }
    .item-card {
        transition: all 0.3s ease;
    }
    .item-card:hover {
        transform: translateY(-5px);
    }
    .badge {
        padding: 0.5em 0.8em;
        font-weight: 500;
    }
    .btn-group .btn {
        border-radius: 0;
    }
    .btn-group .btn:first-child {
        border-top-left-radius: 0.25rem;
        border-bottom-left-radius: 0.25rem;
    }
    .btn-group .btn:last-child {
        border-top-right-radius: 0.25rem;
        border-bottom-right-radius: 0.25rem;
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

// Função para visualizar detalhes do item
function viewItemDetails(itemId) {
    const modal = new bootstrap.Modal(document.getElementById('itemDetailsModal'));
    const modalBody = document.querySelector('#itemDetailsModal .modal-body');
    
    // Mostra loading
    modalBody.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';
    modal.show();
    
    // Carrega detalhes via AJAX
    fetch(`/admin/admin/itens/${itemId}/detalhes`)
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

// Função para ativar/desativar usuário
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