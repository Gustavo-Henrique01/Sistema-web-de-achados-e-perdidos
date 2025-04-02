@extends('admin.dashboard')

@section('content')
<div class="container-fluid mt-4">
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-info-circle me-2"></i>Detalhes do Item</h2>
        <a href="{{ route('admin.listar-itens') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar
        </a>
    </div>

    @if($item)
        <div class="row">
            <!-- Coluna Principal -->
            <div class="col-md-8">
                <!-- Status e Ações -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">{{ $item->titulo }}</h5>
                                <span class="badge bg-{{ $item->status === 'aprovado' ? 'success' : ($item->status === 'reprovado' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>
                            @if($item->status !== 'reprovado' && !$item->excluido_por)
                                <div class="d-flex gap-2">
                                    @if($item->status === 'pendente')
                                        <form action="{{ route('admin.itens.aprovar', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check me-1"></i> Aprovar
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.itens.rejeitar', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-times me-1"></i> Rejeitar
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.itens.remover', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir este item?')">
                                            <i class="fas fa-trash me-1"></i> Excluir
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Fotos do Item -->
                @if($item->fotos->count() > 0)
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-images me-2"></i>Fotos do Item</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach($item->fotos as $foto)
                                    <div class="col-md-4">
                                        <div class="item-photo">
                                            <img src="{{ asset('storage/' . $foto->caminho) }}" alt="Foto do item" class="img-fluid rounded">
                                            <div class="photo-info">
                                                <small class="text-muted">
                                                    <i class="far fa-clock me-1"></i>
                                                    {{ $foto->created_at->format('d/m/Y H:i') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Informações do Item -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Informações do Item</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Categoria</label>
                                <p class="mb-0 fw-bold">{{ $item->categoria->nome_categoria }}</p>
                            </div>
                            @if($item->localizacao)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Localização</label>
                                    <p class="mb-0 fw-bold">{{ $item->localizacao->nome }}</p>
                                </div>
                            @endif
                            @if($item->parceiro)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Parceiro</label>
                                    <p class="mb-0 fw-bold">{{ $item->parceiro->nome }}</p>
                                </div>
                            @endif
                            <div class="col-12 mb-3">
                                <label class="form-label text-muted">Descrição</label>
                                <p class="mb-0">{{ $item->descricao }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Data de Registro</label>
                                <p class="mb-0">{{ $item->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Última Atualização</label>
                                <p class="mb-0">{{ $item->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Histórico de Ações -->
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Histórico de Ações</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            @if($item->aprovado_por)
                                <div class="timeline-item success">
                                    <div class="timeline-icon">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Item Aprovado</h6>
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar-small me-2">
                                                @if($item->aprovadoPor->avatar || $item->aprovadoPor->foto)
                                                    <img src="{{ asset('storage/'.($item->aprovadoPor->avatar ?? $item->aprovadoPor->foto)) }}" alt="Avatar">
                                                @else
                                                    <i class="fas fa-user"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-bold">{{ $item->aprovadoPor->name }}</p>
                                                <small class="text-muted">{{ $item->aprovadoPor->email }}</small>
                                            </div>
                                        </div>
                                        <p class="mb-0 text-muted">
                                            <i class="far fa-clock me-1"></i>
                                            {{ $item->aprovado_em->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if($item->reprovado_por)
                                <div class="timeline-item danger">
                                    <div class="timeline-icon">
                                        <i class="fas fa-times"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Item Reprovado</h6>
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar-small me-2">
                                                @if($item->reprovadoPor->avatar || $item->reprovadoPor->foto)
                                                    <img src="{{ asset('storage/'.($item->reprovadoPor->avatar ?? $item->reprovadoPor->foto)) }}" alt="Avatar">
                                                @else
                                                    <i class="fas fa-user"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-bold">{{ $item->reprovadoPor->name }}</p>
                                                <small class="text-muted">{{ $item->reprovadoPor->email }}</small>
                                            </div>
                                        </div>
                                        <p class="mb-0 text-muted">
                                            <i class="far fa-clock me-1"></i>
                                            {{ $item->reprovado_em->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if($item->excluido_por)
                                <div class="timeline-item danger">
                                    <div class="timeline-icon">
                                        <i class="fas fa-trash"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Item Excluído</h6>
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar-small me-2">
                                                @if($item->excluidoPor->avatar || $item->excluidoPor->foto)
                                                    <img src="{{ asset('storage/'.($item->excluidoPor->avatar ?? $item->excluidoPor->foto)) }}" alt="Avatar">
                                                @else
                                                    <i class="fas fa-user"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-bold">{{ $item->excluidoPor->name }}</p>
                                                <small class="text-muted">{{ $item->excluidoPor->email }}</small>
                                            </div>
                                        </div>
                                        <p class="mb-0 text-muted">
                                            <i class="far fa-clock me-1"></i>
                                            {{ $item->excluido_em->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if(!$item->aprovado_por && !$item->reprovado_por && !$item->excluido_por)
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Nenhuma ação registrada.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coluna Lateral -->
            <div class="col-md-4">
                <!-- Informações do Usuário -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user me-2"></i>Usuário que Cadastrou</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div class="avatar-profile">
                                @if($item->usuario->avatar || $item->usuario->foto)
                                    <img src="{{ asset('storage/'.($item->usuario->avatar ?? $item->usuario->foto)) }}" alt="Foto do Perfil">
                                @else
                                    <div class="avatar-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="text-center">
                            <h5 class="mb-1">{{ $item->usuario->name }}</h5>
                            <p class="text-muted mb-2">{{ $item->usuario->email }}</p>
                            <p class="text-muted mb-0">
                                <i class="far fa-clock me-1"></i>
                                Cadastrado em {{ $item->usuario->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Resumo do Item -->
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Resumo</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-images me-2 text-primary"></i>
                                {{ $item->fotos->count() }} foto(s)
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                {{ $item->localizacao ? 'Localização definida' : 'Sem localização' }}
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-handshake me-2 text-primary"></i>
                                {{ $item->parceiro ? 'Parceiro definido' : 'Sem parceiro' }}
                            </li>
                            <li>
                                <i class="fas fa-calendar me-2 text-primary"></i>
                                {{ $item->created_at->diffForHumans() }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Item não encontrado ou não disponível.
        </div>
    @endif
</div>

<style>
    .avatar-profile {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto;
        border: 3px solid #fff;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .avatar-profile img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .avatar-placeholder {
        width: 100%;
        height: 100%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .avatar-placeholder i {
        font-size: 2rem;
        color: #adb5bd;
    }
    .avatar-small {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e9ecef;
    }
    .avatar-small img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .avatar-small i {
        color: #adb5bd;
    }
    .timeline {
        position: relative;
        padding: 20px 0;
    }
    .timeline-item {
        position: relative;
        padding-left: 50px;
        margin-bottom: 30px;
    }
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    .timeline-icon {
        position: absolute;
        left: 0;
        top: 0;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    .timeline-item.success .timeline-icon {
        background: #28a745;
    }
    .timeline-item.danger .timeline-icon {
        background: #dc3545;
    }
    .timeline-content {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .timeline-content h6 {
        color: #495057;
        margin-bottom: 10px;
    }
    .timeline-item:before {
        content: '';
        position: absolute;
        left: 15px;
        top: 32px;
        bottom: -30px;
        width: 2px;
        background: #e9ecef;
    }
    .timeline-item:last-child:before {
        display: none;
    }
    .item-photo {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .item-photo img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    .photo-info {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 8px;
        background: rgba(0,0,0,0.5);
        color: white;
    }
</style>
@endsection 