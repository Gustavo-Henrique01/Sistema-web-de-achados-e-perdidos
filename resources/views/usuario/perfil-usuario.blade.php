@extends('usuario.home')

@section('content')
<style>
    /* Estilos para o perfil do usuário */
    .profile-card {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        margin-bottom: 30px;
    }
    
    .profile-header {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        padding: 20px;
        position: relative;
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 5px solid white;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        margin: 0 auto;
        overflow: hidden;
        position: relative;
        top: 60px;
        margin-bottom: 30px;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .profile-info {
        padding: 80px 20px 20px;
        background-color: white;
    }
    
    .profile-stats {
        background-color: #f8f9fc;
        padding: 15px;
        border-top: 1px solid #e3e6f0;
        font-size: 0.875rem;
    }
    
    .stat-item {
        padding: 8px 0;
        border-bottom: 1px dashed #e3e6f0;
    }
    
    .stat-item:last-child {
        border-bottom: none;
    }
    
    /* Estilos para os itens cadastrados */
    .items-container {
        margin-bottom: 30px;
    }
    
    .items-header {
        background: linear-gradient(135deg, #36b9cc 0%, #1a8a98 100%);
        color: white;
        padding: 20px;
        border-radius: 15px 15px 0 0;
    }
    
    .items-body {
        background-color: white;
        border-radius: 0 0 15px 15px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        padding: 20px;
    }
    
    .item-card {
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.1);
        height: 100%;
    }
    
    .item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.2);
    }
    
    .item-gallery {
        position: relative;
    }
    
    .main-photo {
        height: 200px;
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
        width: 50px;
        height: 50px;
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
    
    .item-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: bold;
        text-transform: uppercase;
    }
    
    .badge-achado {
        background-color: #1cc88a;
        color: white;
    }
    
    .badge-perdido {
        background-color: #e74a3b;
        color: white;
    }
    
    .item-content {
        padding: 15px;
    }
    
    .item-title {
        font-weight: 600;
        margin-bottom: 10px;
        color: #4e73df;
    }
    
    .item-info {
        font-size: 0.875rem;
        color: #5a5c69;
        margin-bottom: 5px;
    }
    
    .item-footer {
        background-color: #f8f9fc;
        border-top: 1px solid #e3e6f0;
        padding: 10px 15px;
        display: flex;
        justify-content: space-between;
    }
    
    .pagination-container {
        margin-top: 20px;
    }
    
    .no-items {
        padding: 30px;
        text-align: center;
        color: #6c757d;
    }
    
    .no-items i {
        font-size: 3rem;
        margin-bottom: 10px;
        color: #ddd;
    }
    
    /* Estilos para o filtro de status */
    .status-filter {
        margin-bottom: 20px;
    }
    
    .filter-btn {
        border-radius: 20px;
        font-size: 0.85rem;
        margin-right: 5px;
        padding: 5px 15px;
        opacity: 0.7;
        transition: all 0.2s;
    }
    
    .filter-btn:hover, .filter-btn.active {
        opacity: 1;
        transform: translateY(-2px);
    }
    
    .filter-btn.all {
        background-color: #4e73df;
        border-color: #4e73df;
    }
    
    .filter-btn.approved {
        background-color: #1cc88a;
        border-color: #1cc88a;
    }
    
    .filter-btn.pending {
        background-color: #f6c23e;
        border-color: #f6c23e;
    }
    
    .filter-btn.rejected {
        background-color: #e74a3b;
        border-color: #e74a3b;
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
        .profile-avatar {
            width: 100px;
            height: 100px;
            top: 50px;
        }
        
        .profile-info {
            padding-top: 70px;
        }
        
        .main-photo {
            height: 150px;
        }
    }
</style>

<div class="container mt-5">
    <div class="row">
        <!-- Perfil do Usuário -->
        <div class="col-lg-4 mb-4">
            <div class="profile-card">
                <div class="profile-header">
                    <h4 class="mb-0">Perfil do Usuário</h4>
                    <p class="text-white-50">Membro desde {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</p>
                </div>
                
                <!-- Avatar do Usuário -->
                <div class="profile-avatar">
                    @if ($user->foto)
                        <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto do Usuário">
                    @else
                        <span class="text-muted">{{ substr($user->name, 0, 1) }}</span>
                    @endif
                </div>
                
                <!-- Informações do Usuário -->
                <div class="profile-info">
                    <h4 class="text-center mb-4">{{ $user->name }}</h4>
                    
                    <div class="profile-stats">
                        <div class="stat-item">
                            <div class="row">
                                <div class="col-5"><strong>Email:</strong></div>
                                <div class="col-7 text-end">{{ $user->email }}</div>
                            </div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="row">
                                <div class="col-5"><strong>Telefone:</strong></div>
                                <div class="col-7 text-end">{{ $user->telefone ?: 'Não informado' }}</div>
                            </div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="row">
                                <div class="col-5"><strong>CPF:</strong></div>
                                <div class="col-7 text-end">{{ $user->cpf ?: 'Não informado' }}</div>
                            </div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="row">
                                <div class="col-5"><strong>Status:</strong></div>
                                <div class="col-7 text-end">
                                    <span class="badge {{ $user->ativo ? 'bg-success' : 'bg-danger' }}">
                                        {{ $user->ativo ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="row">
                                <div class="col-5"><strong>Função:</strong></div>
                                <div class="col-7 text-end">{{ $user->role->value }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Botões de Ação -->
                <div class="profile-stats">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-primary">Editar Perfil</a>
                        <a href="{{ url('/chatify') }}" class="btn btn-success">
                            <i class="fas fa-comments me-2"></i>Mensagens
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Estatísticas do Usuário -->
            <div class="profile-card">
                <div class="profile-header">
                    <h4 class="mb-0">Estatísticas</h4>
                </div>
                
                <div class="profile-info p-3">
                    <div class="row text-center">
                        <div class="col-6">
                            <h4>{{ $user->itens->count() }}</h4>
                            <p class="text-muted mb-0">Itens cadastrados</p>
                        </div>
                        <div class="col-6">
                            <h4>{{ $user->itens->where('status', 'aprovado')->count() }}</h4>
                            <p class="text-muted mb-0">Itens aprovados</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mensagens Recentes -->
            <div class="profile-card">
                <div class="profile-header">
                    <h4 class="mb-0">Mensagens Recentes</h4>
                </div>
                
                <div class="list-group list-group-flush">
                    <a href="{{ url('/chatify') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-inbox me-2 text-primary"></i>
                            <span>Ver todas as mensagens</span>
                        </div>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Itens Cadastrados -->
        <div class="col-lg-8">
            <div class="items-container">
                <div class="items-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Meus Itens Cadastrados</h4>
                    <a href="{{ route('registrar-item') }}" class="btn btn-light btn-sm">Cadastrar Novo Item</a>
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
                            <a href="{{ route('registrar-item') }}" class="btn btn-primary">Cadastrar novo item</a>
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
                                                    <img src="{{ asset('storage/' . $item->fotos->where('is_principal', true)->first()->caminho ?? $item->fotos->first()->caminho) }}" alt="Foto do Item">
                                                    
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
                                                
                                                <div class="item-badge badge-{{ $item->tipo }}">
                                                    {{ ucfirst($item->tipo) }}
                                                </div>
                                            </div>
                                            
                                            <!-- Miniaturas das fotos (apenas se houver mais de uma foto) -->
                                            @if ($item->fotos && $item->fotos->count() > 1)
                                                <div class="photo-thumbnails">
                                                    @foreach($item->fotos as $index => $foto)
                                                        <div class="photo-thumbnail {{ $foto->is_principal ? 'active' : '' }}" 
                                                             onclick="changeMainPhoto({{ $item->id }}, '{{ asset('storage/' . $foto->caminho) }}', this)">
                                                            <img src="{{ asset('storage/' . $foto->caminho) }}" alt="Miniatura">
                                                        </div>
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
                                            <a href="{{ route('usuario.editar-item', $item->id) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                            
                                            <form action="{{ route('usuario.deletar-item', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este item?');">
                                                    <i class="fas fa-trash"></i> Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Adicionar Font Awesome para ícones -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Scripts para a galeria de fotos -->
<script>
    // Array para armazenar as fotos de cada item
    const itemGalleries = {};
    
    // Inicializa os arrays de fotos para cada item que tem múltiplas fotos
    document.addEventListener('DOMContentLoaded', function() {
        @foreach($user->itens as $item)
            @if($item->fotos && $item->fotos->count() > 1)
                itemGalleries[{{ $item->id }}] = [
                    @foreach($item->fotos as $foto)
                        "{{ asset('storage/' . $foto->caminho) }}",
                    @endforeach
                ];
            @endif
        @endforeach
        
        // Inicializa os filtros
        initStatusFilter();
    });
    
    // Função para filtrar itens por status
    function initStatusFilter() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const itemContainers = document.querySelectorAll('.item-container');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove a classe active de todos os botões
                filterButtons.forEach(btn => btn.classList.remove('active'));
                
                // Adiciona a classe active ao botão clicado
                this.classList.add('active');
                
                const selectedStatus = this.getAttribute('data-status');
                
                // Filtra os itens com base no status selecionado
                itemContainers.forEach(container => {
                    if (selectedStatus === 'todos' || container.getAttribute('data-status') === selectedStatus) {
                        container.style.display = '';
                    } else {
                        container.style.display = 'none';
                    }
                });
                
                // Verifica se há itens visíveis para o status atual
                checkNoVisibleItems(selectedStatus);
            });
        });
    }
    
    // Verifica se há itens visíveis para o status atual e exibe mensagem se não houver
    function checkNoVisibleItems(status) {
        const visibleItems = document.querySelectorAll(`.item-container[style="display: "]`);
        const noItemsContainer = document.querySelector('.no-visible-items');
        
        if (visibleItems.length === 0) {
            // Se não existir, cria o container para a mensagem
            if (!noItemsContainer) {
                const itemsRow = document.querySelector('.row');
                const noVisibleItemsDiv = document.createElement('div');
                noVisibleItemsDiv.className = 'no-visible-items no-items w-100';
                noVisibleItemsDiv.innerHTML = `
                    <i class="fas fa-filter d-block"></i>
                    <p>Nenhum item ${status !== 'todos' ? `com status "${status}"` : ''} encontrado.</p>
                `;
                itemsRow.appendChild(noVisibleItemsDiv);
            } else {
                noItemsContainer.style.display = '';
                const statusText = status !== 'todos' ? `com status "${status}"` : '';
                noItemsContainer.querySelector('p').innerText = `Nenhum item ${statusText} encontrado.`;
            }
        } else if (noItemsContainer) {
            noItemsContainer.style.display = 'none';
        }
    }
    
    // Função para trocar a foto principal ao clicar em uma miniatura
    function changeMainPhoto(itemId, photoUrl, thumbnailElement) {
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
    function prevPhoto(itemId) {
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
    function nextPhoto(itemId) {
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
</script>
@endsection