<!-- Itens Cadastrados -->
<div class="items-container">
    <div class="items-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Meus Itens Cadastrados</h4>
        <a href="{{ route('usuario.cadastrar-item') }}" class="btn btn-light btn-sm">
            <i class="fas fa-plus me-2"></i>Cadastrar Novo Item
        </a>
    </div>
    
    <div class="items-body">
        <!-- Filtro de status -->
        <div class="status-filter">
            <div class="d-flex flex-wrap justify-content-center">
                <button type="button" class="btn btn-sm filter-btn all active" data-status="todos">
                    <i class="fas fa-list"></i> Todos <span class="badge bg-white text-primary">{{ $user->itens->count() }}</span>
                </button>
                <button type="button" class="btn btn-sm filter-btn approved" data-status="aprovado">
                    <i class="fas fa-check-circle"></i> Aprovados <span class="badge bg-white text-success">{{ $user->itens->where('status', 'aprovado')->count() }}</span>
                </button>
                <button type="button" class="btn btn-sm filter-btn pending" data-status="pendente">
                    <i class="fas fa-clock"></i> Pendentes <span class="badge bg-white text-warning">{{ $user->itens->where('status', 'pendente')->count() }}</span>
                </button>
                <button type="button" class="btn btn-sm filter-btn rejected" data-status="reprovado">
                    <i class="fas fa-times-circle"></i> Reprovados <span class="badge bg-white text-danger">{{ $user->itens->where('status', 'reprovado')->count() }}</span>
                </button>
            </div>
        </div>
        
        @if ($user->itens->isEmpty())
            <div class="no-items">
                <i class="fas fa-box-open d-block"></i>
                <p>Você ainda não cadastrou nenhum item.</p>
                <a href="{{ route('usuario.cadastrar-item') }}" class="btn btn-primary">Cadastrar novo item</a>
            </div>
        @else
            <div class="row">
                @foreach ($user->itens as $item)
                    <div class="col-md-6 mb-4 item-container" data-status="{{ $item->status }}">
                        <div class="item-card">
                            <div class="item-gallery">
                                <!-- Foto principal -->
                                <div class="main-photo" id="main-photo-{{ $item->id }}">
                                    @if ($item->fotos && $item->fotos->isNotEmpty())
                                        @php
                                            $fotoPrincipal = $item->fotos->where('is_principal', true)->first();
                                            $foto = $fotoPrincipal ?? $item->fotos->first();
                                        @endphp
                                        @if($foto && $foto->caminho)
                                            <img src="{{ asset('storage/' . $foto->caminho) }}" alt="Foto do Item">
                                            
                                            <!-- Navegação da galeria (apenas se houver mais de uma foto) -->
                                            @if($item->fotos->count() > 1)
                                                <div class="gallery-nav gallery-prev" onclick="prevPhoto({{ $item->id }})">
                                                    <i class="fas fa-chevron-left"></i>
                                                </div>
                                                <div class="gallery-nav gallery-next" onclick="nextPhoto({{ $item->id }})">
                                                    <i class="fas fa-chevron-right"></i>
                                                </div>
                                            @endif
                                        @else
                                            <div class="d-flex align-items-center justify-content-center h-100">
                                                <i class="fas fa-image text-muted fa-3x"></i>
                                            </div>
                                        @endif
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100">
                                            <i class="fas fa-image text-muted fa-3x"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="item-badge badge-{{ $item->tipo }}">
                                        {{ ucfirst($item->tipo) }}
                                    </div>
                                </div>
                                
                                <!-- Miniaturas das fotos (apenas se houver mais de uma foto) -->
                                @if ($item->fotos && $item->fotos->count() > 1)
                                    <div class="photo-thumbnails">
                                        @foreach($item->fotos as $foto)
                                            @if($foto->caminho)
                                                <div class="photo-thumbnail {{ $foto->is_principal ? 'active' : '' }}" 
                                                     onclick="changeMainPhoto({{ $item->id }}, '{{ asset('storage/' . $foto->caminho) }}', this)">
                                                    <img src="{{ asset('storage/' . $foto->caminho) }}" alt="Miniatura">
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            
                            <div class="item-content">
                                <div class="item-title">{{ $item->categoria->nome_categoria }}</div>
                                
                                <div class="item-info">
                                    <p class="text-truncate"><strong>Descrição:</strong> {{ $item->descricao }}</p>
                                    
                                    @if($item->tipo == 'perdido' && $item->data_perdido)
                                        <p><strong>Perdido em:</strong> {{ \Carbon\Carbon::parse($item->data_perdido)->format('d/m/Y') }}</p>
                                    @elseif($item->tipo == 'achado' && $item->data_encontrado)
                                        <p><strong>Encontrado em:</strong> {{ \Carbon\Carbon::parse($item->data_encontrado)->format('d/m/Y') }}</p>
                                    @endif
                                    
                                    <p><strong>Local:</strong> {{ $item->localizacao->nome_local ?? 'Não informado' }}</p>
                                    
                                    <p>
                                        <strong>Status:</strong> 
                                        <span class="badge {{ $item->status == 'aprovado' ? 'bg-success' : ($item->status == 'pendente' ? 'bg-warning' : 'bg-danger') }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="item-footer">
                                <div class="d-flex flex-wrap gap-1 justify-content-between w-100">
                                    <div>
                                        @if($item->status === 'pendente' || $item->status === 'reprovado')
                                        <a href="{{ route('usuario.editar-item', $item->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        @endif
                                    </div>
                                    <div>
                                        @if($item->status === 'aprovado' && !$item->parceiro_id && $item->tipo === 'achado')
                                        <button type="button" 
                                                class="btn btn-success btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#enviarParaParceiroModal-{{ $item->id }}">
                                            <i class="fas fa-store"></i> Enviar para Ponto de Coleta
                                        </button>
                                        @endif
                                        
                                        @if($item->status === 'aprovado')
                                        <button type="button" 
                                                class="btn btn-info btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#marcarDevolvidoModal-{{ $item->id }}">
                                            <i class="fas fa-handshake"></i> Marcar como Devolvido
                                        </button>
                                        @elseif($item->status === 'devolvido')
                                        <span class="badge bg-success p-2">
                                            <i class="fas fa-check-circle me-1"></i> Item Devolvido
                                        </span>
                                        @endif
                                        
                                        @if($item->status === 'pendente')
                                        <form action="{{ route('usuario.deletar-item', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este item?');">
                                                <i class="fas fa-trash"></i> Excluir
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
