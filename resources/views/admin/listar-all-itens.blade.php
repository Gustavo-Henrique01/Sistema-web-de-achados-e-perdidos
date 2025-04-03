@extends('admin.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-clipboard-list me-2"></i>Gerenciamento de Itens</h2>
        <div class="d-flex">
            <form method="GET" action="{{ route('admin.listar-itens') }}" class="d-flex" id="filterForm">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Buscar item..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Dashboard de Status -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <a href="{{ route('admin.listar-itens', ['status' => 'todos']) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 {{ $status == 'todos' ? 'bg-primary text-white' : '' }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">Todos os Itens</h6>
                                <h2 class="mt-2 mb-0">{{ App\Models\Item::count() }}</h2>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-boxes fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('admin.listar-itens', ['status' => 'pendente']) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 {{ $status == 'pendente' ? 'bg-warning text-dark' : '' }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">Pendentes</h6>
                                <h2 class="mt-2 mb-0">{{ App\Models\Item::where('status', 'pendente')->count() }}</h2>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('admin.listar-itens', ['status' => 'aprovado']) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 {{ $status == 'aprovado' ? 'bg-success text-white' : '' }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">Aprovados</h6>
                                <h2 class="mt-2 mb-0">{{ App\Models\Item::where('status', 'aprovado')->count() }}</h2>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-3">
            <a href="{{ route('admin.listar-itens', ['status' => 'reprovado']) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 {{ $status == 'reprovado' ? 'bg-danger text-white' : '' }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">Reprovados</h6>
                                <h2 class="mt-2 mb-0">{{ App\Models\Item::where('status', 'reprovado')->count() }}</h2>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-times-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    
    <!-- Filtros Adicionais -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filtros</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.listar-itens') }}" class="row" id="advancedFilterForm">
                <input type="hidden" name="status" value="{{ $status }}">
                
                <div class="col-md-3 mb-3">
                    <label for="tipo" class="form-label">Tipo</label>
                    <select name="tipo" id="tipo" class="form-select">
                        <option value="">Todos</option>
                        <option value="achado" {{ request('tipo') == 'achado' ? 'selected' : '' }}>Achados</option>
                        <option value="perdido" {{ request('tipo') == 'perdido' ? 'selected' : '' }}>Perdidos</option>
                    </select>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="categoria" class="form-label">Categoria</label>
                    <select name="categoria" id="categoria" class="form-select">
                        <option value="">Todas</option>
                        @foreach(App\Models\Categoria::all() as $categoria)
                            <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nome_categoria }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="data_inicio" class="form-label">Data Início</label>
                    <input type="date" class="form-control" id="data_inicio" name="data_inicio" value="{{ request('data_inicio') }}">
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="data_fim" class="form-label">Data Fim</label>
                    <input type="date" class="form-control" id="data_fim" name="data_fim" value="{{ request('data_fim') }}">
                </div>
                
                <div class="col-12 text-end">
                    <a href="{{ route('admin.listar-itens') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-undo-alt me-1"></i>Limpar Filtros
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Exibição dos Itens -->
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Lista de Itens</h5>
            <span class="badge bg-primary">{{ $itens->total() }} itens encontrados</span>
        </div>
        
        <div class="card-body">
            @if ($itens->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Nenhum item encontrado com os filtros atuais.</p>
                </div>
            @else
                <div class="row">
                    @foreach ($itens as $item)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm border-0 item-card">
                                <div class="position-relative">
                                    <div class="item-image">
                                        @if ($item->fotos && $item->fotos->isNotEmpty())
                                            <img src="{{ asset('storage/' . $item->fotos->first()->caminho) }}" class="card-img-top" alt="Foto do Item">
                                        @else
                                            <div class="no-image-placeholder">
                                                <i class="fas fa-image fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                        
                                        <div class="badge-container">
                                            <span class="badge rounded-pill bg-{{ $item->tipo == 'achado' ? 'success' : 'danger' }} position-absolute top-0 start-0 m-2">
                                                {{ ucfirst($item->tipo) }}
                                            </span>
                                            <span class="badge rounded-pill position-absolute top-0 end-0 m-2 bg-{{ $item->status == 'aprovado' ? 'success' : ($item->status == 'pendente' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <h5 class="card-title text-truncate">
                                        <i class="fas fa-tag me-1 text-muted"></i>
                                        {{ $item->categoria ? $item->categoria->nome_categoria : 'Sem Categoria' }}
                                    </h5>
                                    
                                    <p class="card-text description-text">
                                        <strong>Descrição:</strong> {{ Str::limit($item->descricao, 100) }}
                                    </p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}
                                        </small>
                                        
                                        <small class="text-muted">
                                            ID: #{{ $item->id }}
                                        </small>
                                    </div>
                                    
                                    <div class="user-info mt-2">
                                        <a href="{{ route('admin.perfilUser', $item->user_id) }}" class="text-decoration-none text-reset">
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-2">
                                                    @if($item->usuario && $item->usuario->foto)
                                                        <img src="{{ asset('storage/'.$item->usuario->foto) }}" alt="Avatar">
                                                    @else
                                                        <div class="avatar-placeholder">
                                                            <i class="fas fa-user"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <strong>{{ $item->usuario->name ?? 'Usuário não encontrado' }}</strong>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="card-footer bg-light">
                                    <div class="d-grid gap-2">
                                        <div class="btn-group" role="group">
                                            @if($item->status != 'aprovado')
                                                <form action="{{ route('admin.itens-aprovar', $item->id) }}" method="POST" class="d-inline flex-fill">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm w-100">
                                                        <i class="fas fa-check-circle me-1"></i> Aprovar
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            @if($item->status != 'reprovado')
                                                <button type="button" class="btn btn-warning btn-sm w-100" 
                                                        onclick="showRejectModal('{{ route('admin.itens-rejeitar', $item->id) }}')">
                                                    <i class="fas fa-times-circle me-1"></i> Rejeitar
                                                </button>
                                            @endif
                                            
                                            <form action="{{ route('admin.DeletarItem', $item->id) }}" method="POST" class="d-inline flex-fill">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Tem certeza que deseja excluir este item?');">
                                                    <i class="fas fa-trash-alt me-1"></i> Excluir
                                                </button>
                                            </form>
                                        </div>
                                        
                                        <button type="button" class="btn btn-primary btn-sm item-details-btn" data-item-id="{{ $item->id }}">
                                            <i class="fas fa-eye me-1"></i> Ver Detalhes
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginação -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $itens->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para detalhes do item -->
<div class="modal fade" id="itemDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalhes do Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="itemDetailsContent">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Adicione este modal no final do seu arquivo, antes do </body> -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rejeitar Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" action="" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="justificativa" class="form-label">Justificativa da Rejeição</label>
                        <textarea class="form-control" id="justificativa" name="justificativa" rows="3" required minlength="10"></textarea>
                        <div class="form-text">Por favor, explique o motivo da rejeição (mínimo 10 caracteres).</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Rejeitar Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .stat-icon {
        opacity: 0.7;
    }
    .item-image {
        height: 200px;
        overflow: hidden;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .no-image-placeholder {
        height: 100%;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
    }
    .description-text {
        height: 50px;
        overflow: hidden;
        margin-bottom: 10px;
    }
    .user-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        overflow: hidden;
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
        border-radius: 50%;
    }
    .btn-group {
        display: flex;
        width: 100%;
    }
    .btn-group form {
        flex: 1;
        padding: 0 2px;
    }
    .badge-container {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        padding: 10px;
    }
    .item-card {
        transition: transform 0.2s;
    }
    .item-card:hover {
        transform: translateY(-5px);
    }
</style>

<!-- Script para carregar detalhes do item via AJAX -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configurar listeners para os botões de detalhes
    const detailButtons = document.querySelectorAll('.item-details-btn');
    
    detailButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.getAttribute('data-item-id');
            showItemDetails(itemId);
        });
    });
    
    // Submissão automática do formulário ao mudar os filtros
    document.querySelectorAll('#advancedFilterForm select, #advancedFilterForm input[type="date"]').forEach(element => {
        element.addEventListener('change', function() {
            document.getElementById('advancedFilterForm').submit();
        });
    });
});

function showItemDetails(itemId) {
    // Mostrar loading
    document.querySelector('#itemDetailsModal .modal-body').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>
        </div>
    `;
    
    // Mostrar o modal
    const modal = new bootstrap.Modal(document.getElementById('itemDetailsModal'));
    modal.show();
    
    // Fazer a requisição AJAX
    fetch(`/admin/itens/${itemId}/detalhes`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        document.querySelector('#itemDetailsModal .modal-body').innerHTML = html;
    })
    .catch(error => {
        document.querySelector('#itemDetailsModal .modal-body').innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                Erro ao carregar detalhes do item. Por favor, tente novamente.
            </div>
        `;
    });
}

function showRejectModal(formAction) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    form.action = formAction;
    
    const bootstrapModal = new bootstrap.Modal(modal);
    bootstrapModal.show();
}

// Validação do formulário
document.getElementById('rejectForm').addEventListener('submit', function(e) {
    const justificativa = document.getElementById('justificativa').value;
    if (justificativa.length < 10) {
        e.preventDefault();
        alert('A justificativa deve ter pelo menos 10 caracteres.');
    }
});
</script>
@endsection
