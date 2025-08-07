@extends('admin.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <div class="bg-white shadow-sm rounded-3 p-4 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2">
            <div class="mb-3 mb-md-0">
                <h2 class="fw-bold mb-0"><i class="fas fa-clipboard-list me-2 text-primary"></i>Gerenciamento de Itens</h2>
                <p class="text-muted mb-0">Visualize, aprove, rejeite e gerencie todos os itens do sistema</p>
            </div>
            <div class="d-flex">
                <form method="GET" action="{{ route('admin.listar-itens') }}" class="d-flex" id="filterForm">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control border" placeholder="Buscar item..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Dashboard de Status -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.listar-itens', ['status' => 'todos']) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden {{ $status == 'todos' ? 'border-primary border-start border-5' : '' }}">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small text-uppercase mb-1">Todos os Itens</p>
                                <h3 class="fw-bold mb-0">{{ App\Models\Item::count() }}</h3>
                            </div>
                            <div class="stat-icon rounded-circle bg-primary bg-opacity-10 p-3">
                                <i class="fas fa-boxes fa-lg text-primary"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 5px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.listar-itens', ['status' => 'pendente']) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden {{ $status == 'pendente' ? 'border-warning border-start border-5' : '' }}">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small text-uppercase mb-1">Pendentes</p>
                                <h3 class="fw-bold mb-0">{{ App\Models\Item::where('status', 'pendente')->count() }}</h3>
                            </div>
                            <div class="stat-icon rounded-circle bg-warning bg-opacity-10 p-3">
                                <i class="fas fa-clock fa-lg text-warning"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 5px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.listar-itens', ['status' => 'aprovado']) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden {{ $status == 'aprovado' ? 'border-success border-start border-5' : '' }}">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small text-uppercase mb-1">Aprovados</p>
                                <h3 class="fw-bold mb-0">{{ App\Models\Item::where('status', 'aprovado')->count() }}</h3>
                            </div>
                            <div class="stat-icon rounded-circle bg-success bg-opacity-10 p-3">
                                <i class="fas fa-check-circle fa-lg text-success"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 5px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('admin.listar-itens', ['status' => 'reprovado']) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden {{ $status == 'reprovado' ? 'border-danger border-start border-5' : '' }}">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small text-uppercase mb-1">Reprovados</p>
                                <h3 class="fw-bold mb-0">{{ App\Models\Item::where('status', 'reprovado')->count() }}</h3>
                            </div>
                            <div class="stat-icon rounded-circle bg-danger bg-opacity-10 p-3">
                                <i class="fas fa-times-circle fa-lg text-danger"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 5px;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    
    <!-- Filtros Adicionais -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
        <div class="card-header bg-white border-bottom">
            <h5 class="fw-bold mb-0"><i class="fas fa-filter me-2 text-primary"></i>Filtros Avançados</h5>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.listar-itens') }}" class="row g-3" id="advancedFilterForm">
                <input type="hidden" name="status" value="{{ $status }}">
                
                <div class="col-lg-3 col-md-6">
                    <label for="categoria" class="form-label small text-uppercase fw-bold text-secondary">Categoria</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-tag text-primary"></i></span>
                        <select name="categoria" id="categoria" class="form-select border-start-0">
                            <option value="">Todas as categorias</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nome_categoria }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <label for="tipo" class="form-label small text-uppercase fw-bold text-secondary">Tipo</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-primary"></i></span>
                        <select name="tipo" id="tipo" class="form-select border-start-0">
                            <option value="">Todos</option>
                            <option value="achado" {{ request('tipo') == 'achado' ? 'selected' : '' }}>Achados</option>
                            <option value="perdido" {{ request('tipo') == 'perdido' ? 'selected' : '' }}>Perdidos</option>
                        </select>
                    </div>
                </div>
                
              
                
                <div class="col-lg-3 col-md-6">
                    <label for="data_inicio" class="form-label small text-uppercase fw-bold text-secondary">Data Início</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-calendar-alt text-primary"></i></span>
                        <input type="date" class="form-control border-start-0" id="data_inicio" name="data_inicio" value="{{ request('data_inicio') }}">
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <label for="data_fim" class="form-label small text-uppercase fw-bold text-secondary">Data Fim</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-calendar-alt text-primary"></i></span>
                        <input type="date" class="form-control border-start-0" id="data_fim" name="data_fim" value="{{ request('data_fim') }}">
                    </div>
                </div>
                
                <div class="col-12 d-flex justify-content-end mt-2">
                    <a href="{{ route('admin.listar-itens') }}" class="btn btn-light me-2">
                        <i class="fas fa-undo-alt me-1"></i>Limpar Filtros
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-search me-1"></i>Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Exibição dos Itens -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
            <h5 class="fw-bold mb-0"><i class="fas fa-list me-2 text-primary"></i>Lista de Itens</h5>
            <span class="badge bg-primary rounded-pill px-3 py-2">{{ $itens->total() }} itens encontrados</span>
        </div>
        
        <div class="card-body">
            @if ($itens->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Nenhum item encontrado com os filtros atuais.</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($itens as $item)
                        <div class="col-xl-4 col-md-6">
                            <div class="card h-100 shadow-sm border-0 item-card rounded-3 overflow-hidden">
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
                                            <span class="badge rounded-pill bg-{{ $item->tipo == 'achado' ? 'success' : 'danger' }} position-absolute top-0 start-0 m-3 px-3 py-2 shadow-sm">
                                                <i class="fas {{ $item->tipo == 'achado' ? 'fa-hand-holding' : 'fa-search' }} me-1"></i> {{ ucfirst($item->tipo) }}
                                            </span>
                                            <span class="badge rounded-pill position-absolute top-0 end-0 m-3 px-3 py-2 shadow-sm bg-{{ $item->status == 'aprovado' ? 'success' : ($item->status == 'pendente' ? 'warning' : 'danger') }}">
                                                <i class="fas {{ $item->status == 'aprovado' ? 'fa-check-circle' : ($item->status == 'pendente' ? 'fa-clock' : 'fa-times-circle') }} me-1"></i> {{ ucfirst($item->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title mb-0 fw-bold">
                                            <span class="badge bg-light text-primary rounded-pill px-3 py-2 me-2">
                                                <i class="fas fa-tag me-1"></i>
                                                {{ $item->categoria ? $item->categoria->nome_categoria : 'Sem Categoria' }}
                                            </span>
                                        </h5>
                                        <small class="text-muted badge bg-light rounded-pill px-2 py-1">
                                            #{{ $item->id }}
                                        </small>
                                    </div>
                                    
                                    <div class="bg-light p-3 rounded-3 mb-3">
                                        <p class="card-text description-text mb-0">
                                            <i class="fas fa-quote-left text-primary opacity-50 me-1"></i>
                                            {{ Str::limit($item->descricao, 100) }}
                                            <i class="fas fa-quote-right text-primary opacity-50 ms-1"></i>
                                        </p>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                            <i class="fas fa-calendar-alt me-1 text-primary"></i>
                                            {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                    
                                    <div class="user-info p-2 bg-light rounded-3">
                                        <a href="{{ route('admin.perfilUser', $item->user_id) }}" class="text-decoration-none text-reset">
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-2 border border-2 border-white shadow-sm">
                                                    @if($item->usuario && $item->usuario->foto)
                                                        <img src="{{ asset('storage/'.$item->usuario->foto) }}" alt="Avatar">
                                                    @else
                                                        <div class="avatar-placeholder">
                                                            <i class="fas fa-user"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="mb-0 fw-medium">{{ $item->usuario->name ?? 'Usuário não encontrado' }}</p>
                                                    <small class="text-muted">Registrado por</small>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="card-footer bg-white border-top-0 p-4 pt-0">
                                    <div class="d-grid gap-2">
                                        <div class="d-flex flex-wrap gap-2 mb-2">
                                            @if($item->status == 'reprovado' || $item->status == 'pendente')
                                                <form action="{{ route('admin.itens-aprovar', $item->id) }}" method="POST" class="d-inline flex-fill">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm w-100 rounded-pill">
                                                        <i class="fas fa-check-circle me-1"></i> Aprovar
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            @if($item->status != 'reprovado')
                                                <button type="button" class="btn btn-warning btn-sm w-100 rounded-pill" 
                                                        onclick="showRejectModal('{{ route('admin.itens-rejeitar', $item->id) }}')">
                                                    <i class="fas fa-times-circle me-1"></i> Rejeitar
                                                </button>
                                            @endif
                                            
                                            <form action="{{ route('admin.DeletarItem', $item->id) }}" method="POST" class="d-inline flex-fill">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-pill" onclick="return confirm('Tem certeza que deseja excluir este item?');">
                                                    <i class="fas fa-trash-alt me-1"></i> Excluir
                                                </button>
                                            </form>
                                        </div>
                                        
                                        <button type="button" class="btn btn-primary btn-sm rounded-pill item-details-btn shadow-sm" data-item-id="{{ $item->id }}">
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
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-info-circle me-2"></i>Detalhes do Item</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body p-0" id="itemDetailsContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                    <p class="mt-3 text-muted">Carregando detalhes do item...</p>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Fechar</button>
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
