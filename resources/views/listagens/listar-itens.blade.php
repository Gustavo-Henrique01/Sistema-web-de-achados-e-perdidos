@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Cabeçalho -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Itens Cadastrados</h2>
                <div>
                    <a href="{{ route('itens.mapa') }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-map-marked-alt me-2"></i>Ver no Mapa
                    </a>
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                        <i class="fas fa-filter me-2"></i>Filtros
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="collapse" id="filtersCollapse">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form id="filterForm" class="row g-3">
                            <!-- Filtro de Tipo -->
                            <div class="col-md-3">
                                <label class="form-label">Tipo</label>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="tipo" id="tipo_todos" value="" checked>
                                    <label class="btn btn-outline-primary" for="tipo_todos">Todos</label>
                                    
                                    <input type="radio" class="btn-check" name="tipo" id="tipo_achados" value="achado">
                                    <label class="btn btn-outline-success" for="tipo_achados">Achados</label>
                                    
                                    <input type="radio" class="btn-check" name="tipo" id="tipo_perdidos" value="perdido">
                                    <label class="btn btn-outline-warning" for="tipo_perdidos">Perdidos</label>
                                </div>
                            </div>

                            <!-- Filtro de Categoria -->
                            <div class="col-md-3">
                                <label for="categoria" class="form-label">Categoria</label>
                                <select class="form-select" id="categoria" name="categoria">
                                    <option value="">Todas as categorias</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}">{{ $categoria->nome_categoria }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtro de Status -->
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">Todos os status</option>
                                    <option value="aprovado">Aprovados</option>
                                    <option value="em_estabelecimento">Em Estabelecimento</option>
                                    <option value="devolvido">Devolvidos</option>
                                </select>
                            </div>

                            <!-- Filtro de Data -->
                            <div class="col-md-3">
                                <label for="data" class="form-label">Data</label>
                                <input type="date" class="form-control" id="data" name="data">
                            </div>

                            <!-- Botão Aplicar Filtros -->
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter me-2"></i>Aplicar Filtros
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Itens -->
    <div class="row" id="itensList">
        @forelse ($itens as $item)
            <div class="col-md-6 col-lg-4 mb-4 item-card" 
                 data-tipo="{{ $item->tipo }}"
                 data-categoria="{{ $item->categoria->id }}"
                 data-status="{{ $item->status }}"
                 data-lat="{{ $item->localizacao->latitude }}"
                 data-lng="{{ $item->localizacao->longitude }}">
                <div class="card h-100 shadow-sm hover-card">
                    <!-- Carrossel de Fotos -->
                    @if($item->fotos->count() > 0)
                        <div id="carousel{{ $item->id }}" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($item->fotos as $index => $foto)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/'.$foto->caminho) }}" 
                                             class="card-img-top" 
                                             alt="Foto do item"
                                             style="height: 200px; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                            @if($item->fotos->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#carousel{{ $item->id }}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carousel{{ $item->id }}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">{{ Str::limit($item->descricao, 30) }}</h5>
                            <span class="badge bg-{{ $item->tipo === 'achado' ? 'success' : 'warning' }}">
                                {{ $item->tipo === 'achado' ? 'Achado' : 'Perdido' }}
                            </span>
                        </div>
                        
                        <p class="card-text text-muted mb-2">
                            <i class="fas fa-tag me-1"></i>
                            {{ $item->categoria->nome_categoria }}
                        </p>
                        
                        <p class="card-text">
                            {{ Str::limit($item->descricao, 100) }}
                        </p>
                        
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ $item->localizacao->endereco }}
                            </small>
                        </div>
                        
                        @if($item->status === 'em_estabelecimento' && $item->parceiro)
                        <div class="mb-3">
                            <small class="text-primary">
                                <i class="fas fa-store me-1"></i>
                                <strong>Em estabelecimento parceiro:</strong> {{ $item->parceiro->nome }}
                            </small>
                        </div>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-{{ $item->status === 'aprovado' ? 'success' : ($item->status === 'em_estabelecimento' ? 'info' : 'secondary') }}">
                                {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                            </span>
                            <a href="{{ route('itens.show', $item->id) }}" 
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-eye me-1"></i>
                                Ver Detalhes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Nenhum item encontrado.
                </div>
            </div>
        @endforelse
    </div>
</div>

@push('styles')
<style>
    .hover-card {
        transition: all 0.3s ease;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .carousel-control-prev,
    .carousel-control-next {
        background-color: rgba(0,0,0,0.5);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
    }
    .badge {
        font-size: 0.8rem;
    }
    .btn-check:checked + .btn-outline-primary {
        background-color: var(--bs-primary);
        color: white;
    }
    .btn-check:checked + .btn-outline-success {
        background-color: var(--bs-success);
        color: white;
    }
    .btn-check:checked + .btn-outline-warning {
        background-color: var(--bs-warning);
        color: white;
    }
    
    
</style>
@endpush

@push('scripts')
<script>
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const tipo = document.querySelector('input[name="tipo"]:checked').value;
        const categoria = document.getElementById('categoria').value;
        const status = document.getElementById('status').value;
        const data = document.getElementById('data').value;

        // Filtra os cards
        document.querySelectorAll('.item-card').forEach(card => {
            const cardTipo = card.dataset.tipo;
            const cardCategoria = card.dataset.categoria;
            const cardStatus = card.dataset.status;
            const cardData = card.dataset.data;

            const tipoMatch = !tipo || cardTipo === tipo;
            const categoriaMatch = !categoria || cardCategoria === categoria;
            const statusMatch = !status || cardStatus === status;
            const dataMatch = !data || cardData === data;

            card.style.display = tipoMatch && categoriaMatch && statusMatch && dataMatch ? 'block' : 'none';
        });
    });
</script>
@endpush
@endsection