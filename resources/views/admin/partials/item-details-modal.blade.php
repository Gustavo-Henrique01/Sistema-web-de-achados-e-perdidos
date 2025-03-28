<div class="row">
    <!-- Galeria de fotos -->
    <div class="col-md-6 mb-4">
        @if($item->fotos && $item->fotos->isNotEmpty())
            <div class="position-relative">
                <div id="itemPhotosCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($item->fotos as $index => $foto)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $foto->caminho) }}" class="d-block w-100 rounded" style="height: 300px; object-fit: cover;" alt="Foto do Item">
                                @if($foto->is_principal)
                                    <div class="position-absolute bottom-0 end-0 mb-2 me-2">
                                        <span class="badge bg-primary">Principal</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @if($item->fotos->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#itemPhotosCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#itemPhotosCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Próximo</span>
                        </button>
                    @endif
                </div>
                <div class="text-center mt-2">
                    <small class="text-muted">{{ $item->fotos->count() }} foto(s)</small>
                </div>
            </div>
        @else
            <div class="border rounded d-flex align-items-center justify-content-center bg-light" style="height: 300px;">
                <div class="text-center text-muted">
                    <i class="fas fa-image fa-4x mb-3"></i>
                    <p>Nenhuma foto disponível</p>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Informações detalhadas -->
    <div class="col-md-6">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Item #{{ $item->id }}</h4>
            <span class="badge rounded-pill bg-{{ $item->tipo == 'achado' ? 'success' : 'danger' }}">
                {{ ucfirst($item->tipo) }}
            </span>
        </div>
        
        <div class="meta-info mb-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="detail-item mb-2">
                        <span class="text-muted"><i class="fas fa-tag me-2"></i>Categoria:</span>
                        <span class="fw-bold">{{ $item->categoria->nome_categoria }}</span>
                    </div>
                    <div class="detail-item mb-2">
                        <span class="text-muted"><i class="fas fa-clock me-2"></i>Status:</span>
                        <span class="badge bg-{{ $item->status == 'aprovado' ? 'success' : ($item->status == 'pendente' ? 'warning' : 'danger') }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </div>
                    <div class="detail-item mb-2">
                        <span class="text-muted"><i class="fas fa-calendar-alt me-2"></i>Registrado em:</span>
                        <span>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($item->aprovado_em)
                        <div class="detail-item mb-2">
                            <span class="text-muted"><i class="fas fa-check-circle me-2"></i>Aprovado em:</span>
                            <span>{{ \Carbon\Carbon::parse($item->aprovado_em)->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-6">
                    @if($item->tipo == 'perdido' && $item->data_perdido)
                        <div class="detail-item mb-2">
                            <span class="text-muted"><i class="fas fa-calendar-minus me-2"></i>Perdido em:</span>
                            <span>{{ \Carbon\Carbon::parse($item->data_perdido)->format('d/m/Y') }}</span>
                        </div>
                    @endif
                    @if($item->tipo == 'achado' && $item->data_encontrado)
                        <div class="detail-item mb-2">
                            <span class="text-muted"><i class="fas fa-calendar-plus me-2"></i>Encontrado em:</span>
                            <span>{{ \Carbon\Carbon::parse($item->data_encontrado)->format('d/m/Y') }}</span>
                        </div>
                    @endif
                    <div class="detail-item mb-2">
                        <span class="text-muted"><i class="fas fa-map-marker-alt me-2"></i>Local:</span>
                        <span>{{ $item->localizacao->nome_local ?? 'Não informado' }}</span>
                    </div>
                    @if($item->parceiro)
                        <div class="detail-item mb-2">
                            <span class="text-muted"><i class="fas fa-handshake me-2"></i>Parceiro:</span>
                            <span>{{ $item->parceiro->nome }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="descrição mb-3">
            <h6 class="border-bottom pb-2"><i class="fas fa-align-left me-2"></i>Descrição</h6>
            <p>{{ $item->descricao }}</p>
        </div>
        
        <div class="usuario-info p-3 border rounded mb-3 bg-light">
            <h6 class="mb-2"><i class="fas fa-user me-2"></i>Registrado por</h6>
            <div class="d-flex align-items-center">
                <div class="me-3" style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden; background-color: #e9ecef; display: flex; align-items: center; justify-content: center;">
                    @if($item->usuario && $item->usuario->foto)
                        <img src="{{ asset('storage/'.$item->usuario->foto) }}" style="width: 100%; height: 100%; object-fit: cover;" alt="Avatar">
                    @else
                        <i class="fas fa-user"></i>
                    @endif
                </div>
                <div>
                    <div class="fw-bold">{{ $item->usuario->name ?? 'Usuário não encontrado' }}</div>
                    <div class="small text-muted">
                        @if($item->usuario)
                            {{ $item->usuario->email }}
                            @if($item->usuario->telefone)
                                | {{ $item->usuario->telefone }}
                            @endif
                        @endif
                    </div>
                </div>
                <a href="{{ route('admin.perfilUser', $item->user_id) }}" class="btn btn-sm btn-outline-primary ms-auto">
                    <i class="fas fa-user-circle me-1"></i>Ver Perfil
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Ações do item -->
<div class="row">
    <div class="col-12">
        <div class="card mt-3">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-cogs me-2"></i>Ações</h6>
            </div>
            <div class="card-body">
                <div class="d-flex gap-2">
                    @if($item->status != 'aprovado')
                        <form action="{{ route('admin.itens-aprovar', $item->id) }}" method="POST" class="d-inline flex-fill">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check-circle me-1"></i> Aprovar Item
                            </button>
                        </form>
                    @endif
                    
                    @if($item->status != 'reprovado')
                        <form action="{{ route('admin.itens-rejeitar', $item->id) }}" method="POST" class="d-inline flex-fill">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="fas fa-times-circle me-1"></i> Rejeitar Item
                            </button>
                        </form>
                    @endif
                    
                    <form action="{{ route('admin.DeletarItem', $item->id) }}" method="POST" class="d-inline flex-fill">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Tem certeza que deseja excluir este item?');">
                            <i class="fas fa-trash-alt me-1"></i> Excluir Item
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 