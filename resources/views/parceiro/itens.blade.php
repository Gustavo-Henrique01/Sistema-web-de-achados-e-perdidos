@extends('layouts.parceiro')

@section('title', 'Itens no Estabelecimento')

@section('content')
<style>
    /* Estilos para os cards de itens */
    .item-card {
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
        border: none;
        border-radius: var(--border-radius);
        overflow: hidden;
    }
    
    .item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .item-image-container {
        position: relative;
        height: 200px;
        overflow: hidden;
        background-color: #f8f9fa;
    }
    
    .item-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }
    
    .item-card:hover .item-image {
        transform: scale(1.05);
    }
    
    .item-image-overlay {
        position: absolute;
        bottom: 0;
        right: 0;
        padding: 0.5rem;
        background: rgba(0, 0, 0, 0.6);
        border-top-left-radius: 0.5rem;
    }
    
    .card-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .badge-status {
        font-size: 0.75rem;
        padding: 0.35rem 0.65rem;
        border-radius: 50rem;
    }
    
    .filter-card {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
    }
    
    .filter-card .card-header {
        background-color: white;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    /* Estilos para paginação */
    .pagination {
        --bs-pagination-active-bg: var(--primary-color);
        --bs-pagination-active-border-color: var(--primary-color);
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
        .item-image-container {
            height: 180px;
        }
    }
</style>

<div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Itens no Estabelecimento</h1>
            <p class="text-muted mb-0">Gerencie todos os itens vinculados ao seu estabelecimento</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('parceiro.cadastrar-item.form') }}" class="btn btn-primary d-flex align-items-center">
                <i class="fas fa-plus me-2"></i><span>Cadastrar Novo Item</span>
            </a>
        </div>
    </div>

    <!-- Alertas -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filtros -->
    <div class="card filter-card border-0 shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="card-title mb-0"><i class="fas fa-filter me-2"></i>Filtros</h5>
            <button class="btn btn-sm btn-outline-secondary d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilters" aria-expanded="false">
                <i class="fas fa-filter me-1"></i>Mostrar/Ocultar
            </button>
        </div>
        <div class="card-body collapse show" id="collapseFilters">
            <form action="{{ route('parceiro.itens') }}" method="GET" class="row g-3">
                <div class="col-md-3 col-sm-6">
                    <label class="form-label">Tipo</label>
                    <select name="tipo" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="achado" {{ request('tipo') == 'achado' ? 'selected' : '' }}>Achado</option>
                        <option value="perdido" {{ request('tipo') == 'perdido' ? 'selected' : '' }}>Perdido</option>
                    </select>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                        <option value="em_estabelecimento" {{ request('status') == 'em_estabelecimento' ? 'selected' : '' }}>Em Estabelecimento</option>
                        <option value="devolvido" {{ request('status') == 'devolvido' ? 'selected' : '' }}>Devolvido</option>
                    </select>
                </div>
                <div class="col-md-4 col-sm-12">
                    <label class="form-label">Buscar</label>
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" placeholder="Buscar por descrição..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-2 col-sm-12 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Itens (Layout de Cards) -->
    <div class="row g-3">
        @forelse($itens as $item)
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card item-card h-100 shadow-sm">
                    <!-- Cabeçalho do Card -->
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <div>
                            <span class="badge {{ $item->tipo == 'achado' ? 'bg-success' : 'bg-warning' }} me-2">
                                <i class="fas fa-{{ $item->tipo == 'achado' ? 'hand-holding-heart' : 'search' }} me-1"></i>
                                {{ ucfirst($item->tipo) }}
                            </span>
                            <span class="badge {{ $item->status == 'devolvido' ? 'bg-info' : ($item->status == 'em_estabelecimento' ? 'bg-primary' : 'bg-secondary') }}">
                                <i class="fas fa-{{ $item->status == 'devolvido' ? 'check-circle' : ($item->status == 'em_estabelecimento' ? 'box' : 'clock') }} me-1"></i>
                                {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                            </span>
                        </div>
                        <small class="text-muted"><i class="far fa-calendar-alt me-1"></i>{{ $item->created_at->format('d/m/Y') }}</small>
                    </div>
                    
                    <!-- Corpo do Card -->
                    <div class="card-body">
                        <!-- Fotos do Item -->
                        @if($item->fotos && $item->fotos->count() > 0)
                            <div class="item-image-container mb-3">
                                <img src="{{ asset('storage/' . $item->fotos->first()->caminho) }}" 
                                     alt="{{ $item->descricao }}" 
                                     class="item-image">
                                <div class="item-image-overlay">
                                    <span class="badge bg-dark">
                                        <i class="fas fa-camera me-1"></i>{{ $item->fotos->count() }} foto{{ $item->fotos->count() > 1 ? 's' : '' }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="item-image-container mb-3 bg-light d-flex align-items-center justify-content-center">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif

                        <div>
                            <h5 class="card-title mb-2 text-truncate">{{ Str::limit($item->descricao, 50) }}</h5>
                            <p class="card-text text-muted small mb-0">{{ Str::limit($item->descricao, 100) }}</p>
                        </div>
                    </div>
                    
                    <!-- Rodapé do Card com Ações -->
                    <div class="card-footer bg-white border-top-0 pt-0">
                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2">
                            <a href="{{ route('parceiro.itens.show', $item) }}" 
                               class="btn btn-sm btn-primary d-flex align-items-center justify-content-center">
                                <i class="fas fa-eye me-1"></i> Ver Detalhes
                            </a>
                            
                            <div class="d-flex flex-wrap gap-2">
                                @if($item->status === 'em_estabelecimento')
                                    <button type="button" 
                                            class="btn btn-sm btn-success d-flex align-items-center"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#devolverItemModal{{ $item->id }}"
                                            title="Marcar como devolvido">
                                        <i class="fas fa-hand-holding me-1"></i> Devolvido
                                    </button>
                                    
                                    <!-- Botões de Editar e Excluir -->
                                    @if($item->user_id === auth()->id())
                                    <a href="{{ route('parceiro.itens.editar', $item) }}" 
                                       class="btn btn-sm btn-warning d-flex align-items-center"
                                       title="Editar item">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <button type="button" 
                                            class="btn btn-sm btn-danger d-flex align-items-center"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#excluirItemModal{{ $item->id }}"
                                            title="Excluir item">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                @endif
                                
                                <form action="{{ route('parceiro.desvincular-item', $item) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Tem certeza que deseja desvincular este item?');">
                                    @csrf
                                    <button type="submit" 
                                            class="btn btn-sm btn-outline-danger"
                                            title="Desvincular item">
                                        <i class="fas fa-unlink"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modal de Devolução -->
                <div class="modal fade" id="devolverItemModal{{ $item->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="fas fa-hand-holding me-2"></i>Marcar Item como Devolvido</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('parceiro.itens.marcar-devolvido', $item) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Importante:</strong> Ao marcar um item como devolvido, você está confirmando que ele foi entregue ao seu dono legítimo.
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="observacoes_devolucao{{ $item->id }}" class="form-label">Observações da Devolução <span class="text-danger">*</span></label>
                                        <textarea class="form-control" 
                                                  id="observacoes_devolucao{{ $item->id }}" 
                                                  name="observacoes" 
                                                  rows="4" 
                                                  placeholder="Descreva como foi o processo de devolução, quem retirou o item, documentos apresentados, etc."
                                                  required 
                                                  minlength="10"
                                                  maxlength="500"></textarea>
                                        <div class="form-text">
                                            <i class="fas fa-lightbulb text-warning me-1"></i>
                                            Forneça detalhes sobre como o item foi devolvido, quem o retirou, quais documentos foram apresentados, etc. (mínimo 10 caracteres).
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check me-1"></i>Confirmar Devolução
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Modal de Exclusão -->
                <div class="modal fade" id="excluirItemModal{{ $item->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Confirmar Exclusão</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Tem certeza que deseja excluir este item?</p>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-circle me-2"></i><strong>Atenção:</strong> Esta ação não pode ser desfeita.
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <form action="{{ route('parceiro.itens.excluir', $item) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Excluir Item</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Nenhum item encontrado</h5>
                        <p class="mb-0">Tente ajustar os filtros ou cadastre novos itens para seu estabelecimento.</p>
                        <div class="mt-3">
                            <a href="{{ route('parceiro.cadastrar-item.form') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Cadastrar Novo Item
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- Paginação -->
    @if($itens->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $itens->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection