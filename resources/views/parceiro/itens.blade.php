@extends('layouts.parceiro')

@section('title', 'Itens no Estabelecimento')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Itens no Estabelecimento</h1>
            <p class="text-muted mb-0">Gerencie todos os itens vinculados ao seu estabelecimento</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('parceiro.vincular-item.form') }}" class="btn btn-primary d-flex align-items-center">
                <i class="fas fa-plus me-2"></i><span>Vincular Novo Item</span>
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="card-title mb-0">Filtros</h5>
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
                <div class="card h-100 border-0 shadow-sm hover-shadow">
                    <!-- Cabeçalho do Card -->
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <div>
                            <span class="badge bg-{{ $item->tipo == 'achado' ? 'success' : 'warning' }} me-2">
                                {{ ucfirst($item->tipo) }}
                            </span>
                            <span class="badge bg-{{ $item->status == 'devolvido' ? 'info' : ($item->status == 'em_estabelecimento' ? 'primary' : 'secondary') }}">
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
                            <div class="item-image-container mb-3 bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif

                        <div>
                            <h5 class="card-title mb-2">{{ Str::limit($item->descricao, 50) }}</h5>
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
                            
                            <div class="d-flex gap-2">
                                @if($item->status === 'em_estabelecimento')
                                    <button type="button" 
                                            class="btn btn-sm btn-success d-flex align-items-center"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#devolverItemModal{{ $item->id }}"
                                            title="Marcar como devolvido">
                                        <i class="fas fa-hand-holding me-1"></i> Devolvido
                                    </button>
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
                                <h5 class="modal-title">Marcar Item como Devolvido</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('parceiro.itens.marcar-devolvido', $item) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="observacoes" class="form-label">Observações da Devolução</label>
                                        <textarea class="form-control" id="observacoes" name="observacoes" rows="3" required minlength="10"></textarea>
                                        <div class="form-text">Por favor, descreva como foi a devolução do item (mínimo 10 caracteres).</div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Confirmar Devolução</button>
                                </div>
                            </form>
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
                        <p class="mb-0">Tente ajustar os filtros ou vincule novos itens ao seu estabelecimento.</p>
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