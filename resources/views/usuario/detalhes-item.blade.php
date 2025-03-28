@extends('usuario.home')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detalhes do Item</h5>
                    <span class="badge {{ $item->tipo == 'achado' ? 'bg-success' : 'bg-danger' }}">
                        {{ ucfirst($item->tipo) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Galeria de Fotos -->
                        <div class="col-md-6 mb-4">
                            <div class="item-gallery">
                                <!-- Foto principal -->
                                <div class="main-photo" id="main-photo-{{ $item->id }}">
                                    @if ($item->fotos && $item->fotos->isNotEmpty())
                                        <img src="{{ asset('storage/' . $item->fotos->where('is_principal', true)->first()->caminho ?? $item->fotos->first()->caminho) }}" alt="Foto do Item" class="img-fluid rounded">
                                        
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
                                        <div class="d-flex align-items-center justify-content-center h-100 border rounded bg-light">
                                            <i class="fas fa-image text-muted fa-3x"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Miniaturas das fotos (apenas se houver mais de uma foto) -->
                                @if ($item->fotos && $item->fotos->count() > 1)
                                    <div class="photo-thumbnails mt-2">
                                        @foreach($item->fotos as $index => $foto)
                                            <div class="photo-thumbnail {{ $foto->is_principal ? 'active' : '' }}" 
                                                 onclick="changeMainPhoto({{ $item->id }}, '{{ asset('storage/' . $foto->caminho) }}', this)">
                                                <img src="{{ asset('storage/' . $foto->caminho) }}" alt="Miniatura">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Informações do Item -->
                        <div class="col-md-6">
                            <h4 class="border-bottom pb-2 mb-3">{{ $item->categoria->nome_categoria }}</h4>
                            
                            <div class="mb-3">
                                <h6 class="text-muted">Descrição:</h6>
                                <p>{{ $item->descricao }}</p>
                            </div>
                            
                            @if($item->tipo == 'perdido' && $item->data_perdido)
                                <div class="mb-3">
                                    <h6 class="text-muted">Data em que foi perdido:</h6>
                                    <p>{{ \Carbon\Carbon::parse($item->data_perdido)->format('d/m/Y') }}</p>
                                </div>
                            @elseif($item->tipo == 'achado' && $item->data_encontrado)
                                <div class="mb-3">
                                    <h6 class="text-muted">Data em que foi encontrado:</h6>
                                    <p>{{ \Carbon\Carbon::parse($item->data_encontrado)->format('d/m/Y') }}</p>
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                <h6 class="text-muted">Local:</h6>
                                <p>{{ $item->localizacao->nome_local ?? 'Não informado' }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="text-muted">Status:</h6>
                                <span class="badge {{ $item->status == 'aprovado' ? 'bg-success' : ($item->status == 'pendente' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="text-muted">Registrado por:</h6>
                                <p>{{ $item->usuario->name }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="text-muted">Data de registro:</h6>
                                <p>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar com ações e chat -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Ações</h5>
                </div>
                <div class="card-body">
                    @if($item->usuario_id != auth()->id())
                        <div class="d-grid gap-2 mb-3">
                            <a href="{{ url('/chatify/'.$item->usuario_id) }}" class="btn btn-success">
                                <i class="fas fa-comments me-2"></i>Conversar com {{ $item->usuario->name }}
                            </a>
                            <button class="btn btn-primary" id="claimItemBtn">
                                <i class="fas fa-hand-holding me-2"></i>Este é meu item
                            </button>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>Este item foi registrado por você.
                        </div>
                        <div class="d-grid gap-2">
                            <a href="{{ route('usuario.editar-item', $item->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>Editar Item
                            </a>
                            <form action="{{ route('usuario.deletar-item', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Tem certeza que deseja excluir este item?');">
                                    <i class="fas fa-trash me-2"></i>Excluir Item
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Box de informações sobre chat -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Como funciona</h5>
                </div>
                <div class="card-body">
                    <p>Utilize nosso sistema de chat para:</p>
                    <ul>
                        <li>Conversar diretamente com o usuário que registrou o item</li>
                        <li>Combinar detalhes para devolução ou retirada do item</li>
                        <li>Fornecer informações adicionais que comprovem a propriedade</li>
                    </ul>
                    <div class="alert alert-warning">
                        <small><i class="fas fa-exclamation-triangle me-1"></i> Por segurança, recomendamos combinar encontros em locais públicos e movimentados.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para reclamar item -->
<div class="modal fade" id="claimItemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Reclamar Item</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Para reclamar este item, informe detalhes que apenas o proprietário saberia:</p>
                <form id="claimItemForm">
                    <div class="mb-3">
                        <label for="itemDetails" class="form-label">Detalhes do item</label>
                        <textarea class="form-control" id="itemDetails" rows="3" placeholder="Descreva detalhes específicos do item que comprovem que é seu..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="contactInfo" class="form-label">Informações de contato</label>
                        <input type="text" class="form-control" id="contactInfo" placeholder="Seu telefone ou e-mail para contato">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="{{ url('/chatify/'.$item->usuario_id) }}" class="btn btn-primary">Prosseguir para o Chat</a>
            </div>
        </div>
    </div>
</div>

<style>
    .item-gallery {
        position: relative;
    }
    
    .main-photo {
        height: 300px;
        background-color: #e9ecef;
        overflow: hidden;
        position: relative;
    }
    
    .main-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .photo-thumbnails {
        display: flex;
        padding: 5px;
        background-color: rgba(0,0,0,0.03);
        overflow-x: auto;
    }
    
    .photo-thumbnail {
        width: 60px;
        height: 60px;
        margin-right: 5px;
        border: 2px solid #fff;
        border-radius: 4px;
        cursor: pointer;
        overflow: hidden;
        flex-shrink: 0;
    }
    
    .photo-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }
    
    .photo-thumbnail:hover img {
        transform: scale(1.1);
    }
    
    .photo-thumbnail.active {
        border-color: #4e73df;
    }
    
    /* Ícones de navegação da galeria */
    .gallery-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255,255,255,0.7);
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        color: #333;
        transition: all 0.2s;
    }
    
    .gallery-nav:hover {
        background: rgba(255,255,255,0.9);
    }
    
    .gallery-prev {
        left: 10px;
    }
    
    .gallery-next {
        right: 10px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Array para armazenar as fotos do item
        const itemGalleries = {};
        
        @if($item->fotos && $item->fotos->count() > 1)
            itemGalleries[{{ $item->id }}] = [
                @foreach($item->fotos as $foto)
                    "{{ asset('storage/' . $foto->caminho) }}",
                @endforeach
            ];
        @endif
        
        // Função para trocar a foto principal ao clicar em uma miniatura
        window.changeMainPhoto = function(itemId, photoUrl, thumbnailElement) {
            // Atualiza a foto principal
            const mainPhotoContainer = document.getElementById(`main-photo-${itemId}`);
            const mainPhotoImg = mainPhotoContainer.querySelector('img');
            if (mainPhotoImg) {
                mainPhotoImg.src = photoUrl;
            }
            
            // Atualiza a classe ativa na miniatura
            const thumbnails = thumbnailElement.parentElement.querySelectorAll('.photo-thumbnail');
            thumbnails.forEach(thumb => thumb.classList.remove('active'));
            thumbnailElement.classList.add('active');
        }
        
        // Função para navegar para a foto anterior
        window.prevPhoto = function(itemId) {
            if (!itemGalleries[itemId] || itemGalleries[itemId].length <= 1) return;
            
            const mainPhotoContainer = document.getElementById(`main-photo-${itemId}`);
            const mainPhotoImg = mainPhotoContainer.querySelector('img');
            if (!mainPhotoImg) return;
            
            // Encontra o índice atual da foto
            const currentPhotoUrl = mainPhotoImg.src;
            const currentIndex = itemGalleries[itemId].findIndex(url => url === currentPhotoUrl);
            
            // Calcula o índice anterior (com loop circular)
            const prevIndex = (currentIndex - 1 + itemGalleries[itemId].length) % itemGalleries[itemId].length;
            
            // Atualiza a foto principal
            mainPhotoImg.src = itemGalleries[itemId][prevIndex];
            
            // Atualiza a classe ativa na miniatura
            const thumbnails = mainPhotoContainer.parentElement.querySelector('.photo-thumbnails').querySelectorAll('.photo-thumbnail');
            thumbnails.forEach(thumb => thumb.classList.remove('active'));
            thumbnails[prevIndex].classList.add('active');
        }
        
        // Função para navegar para a próxima foto
        window.nextPhoto = function(itemId) {
            if (!itemGalleries[itemId] || itemGalleries[itemId].length <= 1) return;
            
            const mainPhotoContainer = document.getElementById(`main-photo-${itemId}`);
            const mainPhotoImg = mainPhotoContainer.querySelector('img');
            if (!mainPhotoImg) return;
            
            // Encontra o índice atual da foto
            const currentPhotoUrl = mainPhotoImg.src;
            const currentIndex = itemGalleries[itemId].findIndex(url => url === currentPhotoUrl);
            
            // Calcula o próximo índice (com loop circular)
            const nextIndex = (currentIndex + 1) % itemGalleries[itemId].length;
            
            // Atualiza a foto principal
            mainPhotoImg.src = itemGalleries[itemId][nextIndex];
            
            // Atualiza a classe ativa na miniatura
            const thumbnails = mainPhotoContainer.parentElement.querySelector('.photo-thumbnails').querySelectorAll('.photo-thumbnail');
            thumbnails.forEach(thumb => thumb.classList.remove('active'));
            thumbnails[nextIndex].classList.add('active');
        }
        
        // Modal para reclamar item
        const claimItemBtn = document.getElementById('claimItemBtn');
        if (claimItemBtn) {
            claimItemBtn.addEventListener('click', function() {
                const claimItemModal = new bootstrap.Modal(document.getElementById('claimItemModal'));
                claimItemModal.show();
            });
        }
    });
</script>
@endsection 