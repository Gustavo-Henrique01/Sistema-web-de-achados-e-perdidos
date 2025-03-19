@extends('layouts.app')

@section('title', 'Itens Cadastrados')

@section('styles')
<style>
    .filter-section {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .item-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 1;
    }
    
    .item-img {
        height: 200px;
        object-fit: cover;
        transition: transform 0.3s;
    }
    
    .card:hover .item-img {
        transform: scale(1.03);
    }
    
    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }
    
    .empty-state i {
        font-size: 5rem;
        color: #d1d1d1;
        margin-bottom: 20px;
    }
</style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0"><i class="fas fa-list-alt me-2"></i>Itens Cadastrados</h2>
            <a href="{{ route('usuario.cadastrar-item') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Novo Item
            </a>
        </div>
        <p class="text-muted">Encontre itens perdidos ou registre itens encontrados</p>
    </div>
</div>

<!-- Seção de Filtros -->
<div class="filter-section mb-4">
    <form action="{{ route('listar-todos-itens') }}" method="GET" class="row g-3">
        <div class="col-md-4">
            <label for="categoria" class="form-label">Categoria</label>
            <select class="form-select" id="categoria" name="categoria">
                <option value="">Todas as categorias</option>
                @foreach(\App\Models\Categoria::all() as $categoria)
                    <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->nome_categoria }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="col-md-4">
            <label for="tipo" class="form-label">Tipo</label>
            <select class="form-select" id="tipo" name="tipo">
                <option value="">Todos</option>
                <option value="achado" {{ request('tipo') == 'achado' ? 'selected' : '' }}>Achados</option>
                <option value="perdido" {{ request('tipo') == 'perdido' ? 'selected' : '' }}>Perdidos</option>
            </select>
        </div>
        
        <div class="col-md-4">
            <label for="busca" class="form-label">Busca</label>
            <input type="text" class="form-control" id="busca" name="busca" 
                   placeholder="Buscar na descrição..." value="{{ request('busca') }}">
        </div>
        
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter me-1"></i> Filtrar
            </button>
            <a href="{{ route('listar-todos-itens') }}" class="btn btn-outline-secondary">
                <i class="fas fa-redo me-1"></i> Limpar Filtros
            </a>
        </div>
    </form>
</div>

<!-- Listagem de Itens -->
@if(count($itens) > 0)
    <div class="row">
        @foreach ($itens as $item)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm item-card">
                    <!-- Badge de status -->
                    <div class="item-badge">
                        @if($item->tipo == 'perdido')
                            <span class="badge bg-danger">Perdido</span>
                        @else
                            <span class="badge bg-success">Achado</span>
                        @endif
                    </div>
                    
                    <!-- Foto -->
                    <div class="overflow-hidden">
                        <img src="{{ asset('storage/'.$item->foto) }}" class="card-img-top item-img" 
                             alt="Foto do item" onerror="this.src='{{ asset('img/no-image.png') }}'">
                    </div>
                    
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge bg-primary me-2">{{ $item->categoria->nome_categoria }}</span>
                            <small class="text-muted">
                                <i class="far fa-calendar-alt me-1"></i>
                                {{ ($item->tipo == 'perdido') ? 
                                    \Carbon\Carbon::parse($item->data_perdido)->format('d/m/Y') : 
                                    \Carbon\Carbon::parse($item->data_encontrado)->format('d/m/Y') 
                                }}
                            </small>
                        </div>
                        
                        <!-- Descrição -->
                        <h5 class="card-title">{{ \Illuminate\Support\Str::limit($item->descricao, 30) }}</h5>
                        <p class="card-text text-muted">{{ \Illuminate\Support\Str::limit($item->descricao, 100) }}</p>
                        
                        <!-- Localização -->
                        <p class="card-text">
                            <i class="fas fa-map-marker-alt text-danger me-1"></i>
                            {{ $item->localizacao->nome_local }}
                        </p>
                        
                        <!-- Botão de Detalhes -->
                        <a href="#" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#itemModal{{ $item->id }}">
                            <i class="fas fa-eye me-1"></i> Ver Detalhes
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Modal de Detalhes -->
            <div class="modal fade" id="itemModal{{ $item->id }}" tabindex="-1" aria-labelledby="itemModalLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="itemModalLabel{{ $item->id }}">Detalhes do Item</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <img src="{{ asset('storage/'.$item->foto) }}" class="img-fluid rounded" 
                                         alt="Foto do item" onerror="this.src='{{ asset('img/no-image.png') }}'">
                                </div>
                                <div class="col-md-6">
                                    <h5 class="border-bottom pb-2 mb-3">Informações do Item</h5>
                                    
                                    <p><strong>Categoria:</strong> {{ $item->categoria->nome_categoria }}</p>
                                    <p><strong>Tipo:</strong> 
                                        @if($item->tipo == 'perdido')
                                            <span class="badge bg-danger">Perdido</span>
                                        @else
                                            <span class="badge bg-success">Achado</span>
                                        @endif
                                    </p>
                                    <p><strong>Status:</strong> {{ ucfirst($item->status) }}</p>
                                    <p><strong>Data:</strong> 
                                        {{ ($item->tipo == 'perdido') ? 
                                            \Carbon\Carbon::parse($item->data_perdido)->format('d/m/Y') : 
                                            \Carbon\Carbon::parse($item->data_encontrado)->format('d/m/Y') 
                                        }}
                                    </p>
                                    <p><strong>Descrição:</strong> {{ $item->descricao }}</p>
                                    
                                    <h5 class="border-bottom pb-2 mb-3 mt-4">Localização</h5>
                                    <p><strong>Local:</strong> {{ $item->localizacao->nome_local }}</p>
                                    <p><strong>Endereço:</strong> {{ $item->localizacao->endereco }}</p>
                                    
                                    <h5 class="border-bottom pb-2 mb-3 mt-4">Contato</h5>
                                    <p><strong>Nome:</strong> {{ $item->usuario->name }}</p>
                                    <p><strong>Email:</strong> {{ $item->usuario->email }}</p>
                                    <p><strong>Telefone:</strong> {{ $item->usuario->telefone }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="empty-state bg-light rounded p-5">
        <i class="fas fa-search-minus d-block mb-3"></i>
        <h4>Nenhum item encontrado</h4>
        <p class="text-muted">Não encontramos itens para os filtros selecionados.</p>
        <a href="{{ route('usuario.cadastrar-item') }}" class="btn btn-primary mt-3">
            <i class="fas fa-plus-circle me-1"></i> Cadastrar Novo Item
        </a>
    </div>
@endif
@endsection