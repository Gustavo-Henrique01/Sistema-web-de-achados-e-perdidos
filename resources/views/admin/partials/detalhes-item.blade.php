@if($item)
    <div class="container-fluid">
        <!-- Cabeçalho com Status e Ações -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Detalhes do Item #{{ $item->id }}</h5>
                        <span class="badge bg-{{ $item->status === 'aprovado' ? 'success' : ($item->status === 'rejeitado' ? 'danger' : 'warning') }} text-white">
                            {{ ucfirst($item->status) }}
                        </span>
                    </div>
                    <div class="d-flex gap-2">
                        @if($item->status === 'pendente')
                            <form action="{{ route('admin.itens-aprovar', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-2"></i>Aprovar
                                </button>
                            </form>
                            <form action="{{ route('admin.itens-rejeitar', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <div class="modal fade" id="rejectModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Rejeitar Item</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="justificativa" class="form-label">Justificativa da Rejeição</label>
                                                    <textarea class="form-control" id="justificativa" name="justificativa" rows="3" required></textarea>
                                                    <div class="form-text">Por favor, explique o motivo da rejeição.</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-danger">Rejeitar Item</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $item->id }}">
                                    <i class="fas fa-times me-1"></i> Rejeitar
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('admin.DeletarItem', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Tem certeza que deseja remover este item?')">
                                <i class="fas fa-trash me-2"></i>Remover
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid de Informações e Fotos -->
        <div class="row">
            <!-- Coluna da Esquerda - Informações -->
            <div class="col-md-8">
                <!-- Informações Básicas -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informações do Item</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small">Tipo</label>
                                    <p class="mb-0">{{ ucfirst($item->tipo) }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small">Categoria</label>
                                    <p class="mb-0">{{ $item->categoria->nome_categoria ?? 'Categoria não definida' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small">Data de Cadastro</label>
                                    <p class="mb-0">{{ $item->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                @if($item->data_achado)
                                    <div class="mb-3">
                                        <label class="text-muted small">Data em que foi {{ $item->tipo === 'achado' ? 'encontrado' : 'perdido' }}</label>
                                        <p class="mb-0">{{ \Carbon\Carbon::parse($item->data_achado)->format('d/m/Y') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Descrição -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-align-left me-2"></i>Descrição</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $item->descricao }}</p>
                    </div>
                </div>

                <!-- Localização -->
                <div class="card mb-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Localização</h6>
                        <button class="btn btn-sm btn-outline-primary" title="Ver no mapa">
                            <i class="fas fa-map"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        @if($item->localizacao)
                            <div class="mb-3">
                                <label class="text-muted small">Nome do Local</label>
                                <p class="mb-0">{{ $item->localizacao->nome_local ?? 'Não especificado' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Endereço</label>
                                <p class="mb-0">{{ $item->localizacao->endereco ?? 'Não especificado' }}</p>
                            </div>
                            <div>
                                <label class="text-muted small">Referência</label>
                                <p class="mb-0">{{ $item->localizacao->referencia ?? 'Sem referência adicional' }}</p>
                            </div>
                        @else
                            <p class="mb-0 text-muted">Localização não especificada</p>
                        @endif
                    </div>
                </div>

                <!-- Histórico de Ações -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Histórico de Ações</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            @if($item->aprovado_por_id)
                                <div class="timeline-item border-start border-success ps-3 mb-3">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="badge bg-success me-2">Aprovado</span>
                                        <small class="text-muted">{{ $item->aprovado_em->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        @if($item->aprovadoPor->foto)
                                            <img src="{{ asset('storage/'.$item->aprovadoPor->foto) }}" 
                                                 class="rounded-circle me-2" 
                                                 style="width: 32px; height: 32px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-secondary text-white me-2 d-flex align-items-center justify-content-center" 
                                                 style="width: 32px; height: 32px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $item->aprovadoPor->name }}</div>
                                            <small class="text-muted">{{ $item->aprovadoPor->email }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($item->reprovado_por_id)
                                <div class="timeline-item border-start border-danger ps-3 mb-3">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="badge bg-danger me-2">Reprovado</span>
                                        <small class="text-muted">{{ $item->reprovado_em->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        @if($item->reprovadoPor->foto)
                                            <img src="{{ asset('storage/'.$item->reprovadoPor->foto) }}" 
                                                 class="rounded-circle me-2" 
                                                 style="width: 32px; height: 32px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-secondary text-white me-2 d-flex align-items-center justify-content-center" 
                                                 style="width: 32px; height: 32px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $item->reprovadoPor->name }}</div>
                                            <small class="text-muted">{{ $item->reprovadoPor->email }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($item->excluido_por_id)
                                <div class="timeline-item border-start border-dark ps-3 mb-3">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="badge bg-dark me-2">Excluído</span>
                                        <small class="text-muted">{{ $item->excluido_em->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        @if($item->excluidoPor->foto)
                                            <img src="{{ asset('storage/'.$item->excluidoPor->foto) }}" 
                                                 class="rounded-circle me-2" 
                                                 style="width: 32px; height: 32px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-secondary text-white me-2 d-flex align-items-center justify-content-center" 
                                                 style="width: 32px; height: 32px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $item->excluidoPor->name }}</div>
                                            <small class="text-muted">{{ $item->excluidoPor->email }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(!$item->aprovado_por_id && !$item->reprovado_por_id && !$item->excluido_por_id)
                                <div class="text-center text-muted py-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Nenhuma ação administrativa registrada.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coluna da Direita - Fotos e Informações do Usuário -->
            <div class="col-md-4">
                <!-- Fotos -->
                @if($item->fotos->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-images me-2"></i>Fotos ({{ $item->fotos->count() }})</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="row g-0">
                                @foreach($item->fotos as $foto)
                                    <div class="col-6">
                                        <div class="position-relative">
                                            <img src="{{ asset('storage/' . $foto->caminho) }}" 
                                                 class="img-fluid" 
                                                 alt="Foto do item"
                                                 style="width: 100%; height: 200px; object-fit: cover; cursor: pointer;"
                                                 onclick="window.open(this.src, '_blank')">
                                            <div class="position-absolute bottom-0 start-0 w-100 p-2" 
                                                 style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                                <small class="text-white">Clique para ampliar</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Informações do Usuário -->
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fas fa-user me-2"></i>Informações do Usuário</h6>
                        <div class="d-flex gap-2">
                            <a href="/{{ config('chatify.routes.prefix') }}/{{ $item->usuario->id }}" class="btn btn-sm btn-outline-success" title="Conversar com o usuário" target="_blank">
                                <i class="fas fa-comments me-1"></i>Chat
                            </a>
                            <a href="{{ route('admin.perfilUser', $item->usuario->id) }}" class="btn btn-sm btn-outline-primary" title="Ver itens do usuário">
                                <i class="fas fa-list me-1"></i>Ver Itens
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-user-circle fa-2x text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ $item->usuario->name ?? 'Usuário não encontrado' }}</h6>
                                <small class="text-muted">{{ $item->usuario->email ?? 'Não disponível' }}</small>
                            </div>
                        </div>
                        <div class="border-top pt-3">
                            <div class="mb-2">
                                <label class="text-muted small">Telefone</label>
                                <p class="mb-0">{{ $item->usuario->telefone ?? 'Não disponível' }}</p>
                            </div>
                            <div>
                                <label class="text-muted small">Matrícula</label>
                                <p class="mb-0">{{ $item->usuario->matricula ?? 'Não disponível' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle me-2"></i>
        Item não encontrado.
    </div>
@endif

<style>
.timeline-item {
    position: relative;
    padding-bottom: 1rem;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -0.5rem;
    top: 0.25rem;
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    background-color: #fff;
    border: 2px solid;
}

.timeline-item.border-success::before {
    border-color: var(--bs-success);
}

.timeline-item.border-danger::before {
    border-color: var(--bs-danger);
}

.timeline-item.border-dark::before {
    border-color: var(--bs-dark);
}
</style> 