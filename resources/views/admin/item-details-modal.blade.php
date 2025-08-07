@if($item)
    <div class="row g-4 p-4">
        <!-- Coluna Principal -->
        <div class="col-md-8">
            <!-- Status e Ações -->
            <div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="mb-2 mb-md-0">
                            <h4 class="fw-bold mb-2">{{ $item->titulo }}</h4>
                            <div>
                                <span class="badge rounded-pill fs-6 px-3 py-2 bg-{{ $item->status === 'aprovado' ? 'success' : ($item->status === 'reprovado' ? 'danger' : ($item->status === 'em_transferencia' ? 'info' : ($item->status === 'em_estabelecimento' ? 'primary' : ($item->status === 'devolvido' ? 'dark' : 'warning')))) }}">
                                    <i class="fas {{ $item->status === 'aprovado' ? 'fa-check-circle' : ($item->status === 'reprovado' ? 'fa-times-circle' : ($item->status === 'em_transferencia' ? 'fa-exchange-alt' : ($item->status === 'em_estabelecimento' ? 'fa-store' : ($item->status === 'devolvido' ? 'fa-handshake' : 'fa-clock')))) }} me-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                </span>
                            </div>
                        </div>
                        @if($item->status !== 'reprovado' && !$item->excluido_por)
                            <div class="d-flex gap-2 flex-wrap">
                                @if($item->status === 'pendente')
                                    <form action="{{ route('admin.itens-aprovar', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">
                                            <i class="fas fa-check me-1"></i> Aprovar
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.itens-rejeitar', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3">
                                            <i class="fas fa-times me-1"></i> Rejeitar
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.DeletarItem', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="return confirm('Tem certeza que deseja excluir este item?')">
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
                <div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-images me-2"></i>Fotos do Item</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            @foreach($item->fotos as $foto)
                                <div class="col-md-4">
                                    <div class="item-photo position-relative rounded-3 overflow-hidden shadow-sm h-100">
                                        <a href="{{ asset('storage/' . $foto->caminho) }}" data-lightbox="item-photos" data-title="Foto do item">
                                            <img src="{{ asset('storage/' . $foto->caminho) }}" alt="Foto do item" class="img-fluid w-100 h-100" style="object-fit: cover; aspect-ratio: 1/1;">
                                            <div class="photo-overlay position-absolute top-0 left-0 w-100 h-100 d-flex align-items-center justify-content-center">
                                                <i class="fas fa-search-plus text-white fs-4"></i>
                                            </div>
                                        </a>
                                        <div class="photo-info position-absolute bottom-0 start-0 w-100 p-2 bg-dark bg-opacity-75 text-white">
                                            <small>
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
            <div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-clipboard-list me-2"></i>Informações do Item</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="form-label text-dark small text-uppercase fw-bold">Categoria</label>
                                <p class="mb-0 fs-5 fw-medium text-primary">{{ $item->categoria->nome_categoria }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="form-label text-dark small text-uppercase fw-bold">Status</label>
                                <p class="mb-0 fs-5 fw-medium">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</p>
                            </div>
                        </div>
                        @if($item->localizacao)
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="form-label text-dark small text-uppercase fw-bold">Localização</label>
                                <p class="mb-0 fw-medium"><i class="fas fa-map-marker-alt me-2 text-primary"></i>{{ $item->localizacao->nome }}</p>
                            </div>
                        </div>
                        @endif
                        @if($item->parceiro)
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="form-label text-dark small text-uppercase fw-bold">Parceiro</label>
                                <p class="mb-0 fw-medium"><i class="fas fa-store me-2 text-primary"></i>{{ $item->parceiro->nome }}</p>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-12">
                            <div class="info-group">
                                <label class="form-label text-dark small text-uppercase fw-bold">Descrição</label>
                                <p class="mb-0 p-3 bg-light rounded-3 border">{{ $item->descricao }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="form-label text-dark small text-uppercase fw-bold">Registrado por</label>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-small me-2 bg-light">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <p class="mb-0 fw-medium">{{ $item->user->name }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="form-label text-dark small text-uppercase fw-bold">Data de Registro</label>
                                <p class="mb-0"><i class="far fa-calendar-alt me-2 text-primary"></i>{{ $item->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="form-label text-dark small text-uppercase fw-bold">Última Atualização</label>
                                <p class="mb-0"><i class="far fa-clock me-2 text-primary"></i>{{ $item->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Histórico de Ações -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-history me-2"></i>Histórico de Ações</h5>
                </div>
                <div class="card-body p-4">
                    <div class="timeline">
                        @if($item->aprovado_por)
                            <div class="timeline-item success">
                                <div class="timeline-icon bg-success shadow-sm">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="timeline-content bg-white border-0 shadow-sm rounded-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold text-success mb-0"><i class="fas fa-check-circle me-2"></i>Item Aprovado</h6>
                                        <span class="badge bg-light text-dark rounded-pill px-3">
                                            <i class="far fa-clock me-1"></i>
                                            {{ $item->aprovado_em->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center p-2 bg-light rounded-3 mt-2">
                                        <div class="avatar-small me-2 border border-2 border-white shadow-sm">
                                            @if($item->aprovadoPor->avatar || $item->aprovadoPor->foto)
                                                <img src="{{ asset('storage/'.($item->aprovadoPor->avatar ?? $item->aprovadoPor->foto)) }}" alt="Avatar">
                                            @else
                                                <i class="fas fa-user"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-medium">{{ $item->aprovadoPor->name }}</p>
                                            <small class="text-muted">{{ $item->aprovadoPor->email }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($item->reprovado_por)
                            <div class="timeline-item danger">
                                <div class="timeline-icon bg-danger shadow-sm">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <div class="timeline-content bg-white border-0 shadow-sm rounded-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold text-danger mb-0"><i class="fas fa-times-circle me-2"></i>Item Reprovado</h6>
                                        <span class="badge bg-light text-dark rounded-pill px-3">
                                            <i class="far fa-clock me-1"></i>
                                            {{ $item->reprovado_em->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center p-2 bg-light rounded-3 mt-2">
                                        <div class="avatar-small me-2 border border-2 border-white shadow-sm">
                                            @if($item->reprovadoPor->avatar || $item->reprovadoPor->foto)
                                                <img src="{{ asset('storage/'.($item->reprovadoPor->avatar ?? $item->reprovadoPor->foto)) }}" alt="Avatar">
                                            @else
                                                <i class="fas fa-user"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-medium">{{ $item->reprovadoPor->name }}</p>
                                            <small class="text-muted">{{ $item->reprovadoPor->email }}</small>
                                        </div>
                                    </div>
                                    @if($item->motivo_reprovacao)
                                        <div class="mt-3 p-2 border border-danger border-opacity-25 rounded-3 bg-danger bg-opacity-10">
                                            <p class="mb-0 small"><i class="fas fa-info-circle me-2 text-danger"></i><strong>Motivo:</strong> {{ $item->motivo_reprovacao }}</p>
                                        </div>
                                    @endif
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