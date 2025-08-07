@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Detalhes do Item</h3>
            <a href="{{ url()->previous() }}" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Voltar
            </a>
        </div>
        
        <div class="card-body">
            <div class="row">
                <!-- Fotos do item -->
                <div class="col-md-6 mb-4">
                    @if($item->fotos->count() > 0)
                        <div id="itemCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($item->fotos as $index => $foto)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/'.$foto->caminho) }}" 
                                             class="d-block w-100" 
                                             alt="Foto do item"
                                             style="height: 400px; object-fit: contain;">
                                    </div>
                                @endforeach
                            </div>
                            @if($item->fotos->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#itemCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#itemCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Próximo</span>
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" 
                             style="height: 400px;">
                            <i class="fas fa-image fa-5x text-muted"></i>
                        </div>
                    @endif
                </div>
                
                <!-- Informações do item -->
                <div class="col-md-6">
                    <h4 class="border-bottom pb-2 mb-3">Informações Gerais</h4>
                    
                    <div class="mb-3">
                        <span class="badge bg-{{ $item->tipo === 'achado' ? 'success' : 'warning' }} mb-2">
                            {{ $item->tipo === 'achado' ? 'Item Encontrado' : 'Item Perdido' }}
                        </span>
                        <span class="badge bg-{{ $item->status === 'aprovado' ? 'success' : ($item->status === 'pendente' ? 'warning' : 'danger') }} mb-2">
                            {{ ucfirst($item->status) }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Categoria:</strong> {{ $item->categoria->nome_categoria }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Descrição:</strong>
                        <p>{{ $item->descricao }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Localização:</strong>
                        <p>{{ $item->localizacao->endereco }}</p>
                    </div>
                    
                    @if($item->status === 'em_estabelecimento' && $item->parceiro)
                    <div class="mb-3 border-start border-primary ps-3">
                        <strong class="text-primary">
                            <i class="fas fa-store me-1"></i>
                            Item em Estabelecimento Parceiro
                        </strong>
                        <div class="mt-2">
                            <p class="mb-1"><strong>Nome:</strong> {{ $item->parceiro->nome }}</p>
                            <p class="mb-1"><strong>Endereço:</strong> {{ $item->parceiro->endereco }}</p>
                            <p class="mb-1"><strong>Telefone:</strong> {{ $item->parceiro->telefone }}</p>
                            <p class="mb-1"><strong>Horário de Funcionamento:</strong> {{ $item->parceiro->horario_funcionamento }}</p>
                        </div>
                        <div class="mt-2">
                            <a href="/chatify/{{ $item->parceiro->user_id }}" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-comments me-1"></i> Conversar com o Parceiro
                            </a>
                        </div>
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        @if($item->data_perdido)
                            <strong>Data em que foi perdido:</strong>
                            <p>{{ \Carbon\Carbon::parse($item->data_perdido)->format('d/m/Y') }}</p>
                        @endif
                        
                        @if($item->data_encontrado)
                            <strong>Data em que foi encontrado:</strong>
                            <p>{{ \Carbon\Carbon::parse($item->data_encontrado)->format('d/m/Y') }}</p>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <strong>Registrado em:</strong>
                        <p>{{ $item->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    @if(auth()->check() && (auth()->id() === $item->user_id || auth()->user()->isAdmin()))
                        <div class="mt-4">
                            <a href="{{ route('item.edit', $item->id) }}" class="btn btn-warning me-2">
                                <i class="fas fa-edit me-1"></i> Editar
                            </a>
                            
                            <form action="{{ route('item.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este item?')">
                                    <i class="fas fa-trash me-1"></i> Excluir
                                </button>
                            </form>
                        </div>
                    @endif
                    
                    @if(auth()->check() && auth()->id() !== $item->user_id)
                        <div class="mt-4">
                            <a href="{{ route('mensagens.criar', ['destinatario' => $item->user_id, 'assunto' => 'Sobre seu item: ' . substr($item->descricao, 0, 30)]) }}" 
                               class="btn btn-primary">
                                <i class="fas fa-envelope me-1"></i> Entrar em Contato
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection