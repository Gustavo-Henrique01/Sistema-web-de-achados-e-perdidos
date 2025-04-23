@extends('layouts.parceiro')

@section('title', 'Detalhes do Item')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Detalhes do Item</h1>
        <a href="{{ route('parceiro.itens') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Voltar para Itens
        </a>
    </div>

    <div class="row">
        <!-- Informações Principais do Item -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="row">
                        <!-- Imagem do Item -->
                        <div class="col-md-4 mb-4 mb-md-0">
                            <div class="position-relative">
                                @if($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}" 
                                         alt="{{ $item->nome }}" 
                                         class="img-fluid rounded"
                                         style="width: 100%; height: 200px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                         style="width: 100%; height: 200px;">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                @endif
                                <span class="position-absolute top-0 start-0 badge bg-{{ $item->tipo == 'achado' ? 'success' : 'warning' }} m-2">
                                    {{ ucfirst($item->tipo) }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Detalhes do Item -->
                        <div class="col-md-8">
                            <h4 class="mb-3">{{ $item->nome }}</h4>
                            
                            <div class="mb-3">
                                <span class="badge bg-{{ $item->status == 'devolvido' ? 'info' : ($item->status == 'em_estabelecimento' ? 'primary' : 'secondary') }} mb-2">
                                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                </span>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">Descrição</h6>
                                <p>{{ $item->descricao }}</p>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2">Categoria</h6>
                                    <p>{{ $item->categoria->nome_categoria }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2">Data de Registro</h6>
                                    <p>{{ $item->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            
                            @if($item->localizacao)
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">Localização</h6>
                                <p class="mb-0">{{ $item->localizacao->endereco }}</p>
                                @if($item->localizacao->referencia)
                                    <small class="text-muted">{{ $item->localizacao->referencia }}</small>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Ações e Informações Adicionais -->
        <div class="col-lg-4">
            <!-- Ações do Item -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Ações</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($item->status === 'em_estabelecimento')
                            <button type="button" 
                                    class="btn btn-success"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#devolverItemModal">
                                <i class="fas fa-hand-holding me-2"></i>Marcar como Devolvido
                            </button>
                        @endif
                        
                        <form action="{{ route('parceiro.desvincular-item', $item) }}" 
                              method="POST"
                              onsubmit="return confirm('Tem certeza que deseja desvincular este item?');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-unlink me-2"></i>Desvincular Item
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Informações do Usuário -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Informações do Usuário</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-light rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 50px; height: 50px;">
                            <i class="fas fa-user text-muted"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $item->usuario->name }}</h6>
                            <p class="text-muted mb-0 small">{{ $item->usuario->email }}</p>
                        </div>
                    </div>
                    
                    @if($item->usuario->telefone)
                    <div class="mb-2">
                        <i class="fas fa-phone-alt text-muted me-2"></i>
                        {{ $item->usuario->telefone }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de Devolução -->
    <div class="modal fade" id="devolverItemModal" tabindex="-1">
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
@endsection