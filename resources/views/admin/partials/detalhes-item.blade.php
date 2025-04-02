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
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times me-2"></i>Rejeitar
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
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informações Básicas</h6>
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
                        <a href="{{ route('admin.perfilUser', $item->usuario->id) }}" class="btn btn-sm btn-outline-primary" title="Ver itens do usuário">
                            <i class="fas fa-list me-1"></i>Ver Itens
                        </a>
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