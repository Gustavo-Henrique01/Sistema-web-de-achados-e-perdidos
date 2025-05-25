<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Mapa de Itens e Parceiros - Achados e Perdidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #f8fafc;
            --accent-color: #0ea5e9;
            --text-color: #334155;
            --success-color: #22c55e;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-color);
            overflow-x: hidden;
        }
        
        .navbar {
            background-color: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 600;
            color: var(--primary-color) !important;
            font-size: 1.5rem;
        }

        .nav-link {
            color: var(--text-color) !important;
            font-weight: 500;
            position: relative;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--primary-color);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
        }
        
        #map {
            height: calc(100vh - 56px);
            width: 100%;
            min-height: 300px; /* Garantir uma altura mínima para o mapa */
        }
        
        .map-sidebar {
            position: absolute;
            top: 100px;
            left: 15px;
            width: 300px;
            z-index: 1000;
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-height: calc(100vh - 130px);
            overflow-y: auto;
            margin-bottom: 1rem;
        }
        
        .partner-item {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .partner-item:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }
        
        .partner-type {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            display: inline-block;
            margin-top: 0.5rem;
            font-weight: 500;
        }
        
        .tipo-ponto-coleta {
            background-color: #e3f2fd;
            color: var(--primary-color);
        }
        
        .tipo-evento {
            background-color: #fff3cd;
            color: #ffc107;
        }
        
        .tipo-ambos {
            background-color: #d1e7dd;
            color: var(--success-color);
        }
        
        .tipo-achado {
            background-color: #d1e7dd;
            color: var(--success-color);
        }
        
        .tipo-perdido {
            background-color: #fff3cd;
            color: var(--warning-color);
        }
        
        .info-window {
            max-width: 300px;
            padding: 1rem;
        }
        
        .item-card {
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
            background-color: white;
            transition: all 0.3s ease;
        }
        
        .item-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .item-card-header {
            padding: 0.75rem 1rem;
            font-weight: 600;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .item-card-body {
            padding: 1rem;
        }
        
        .item-card-footer {
            padding: 0.75rem 1rem;
            background-color: #f8f9fa;
            border-top: 1px solid #eee;
            font-size: 0.875rem;
        }
        
        .item-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 1rem;
            font-weight: 500;
        }

        .info-window img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        .filter-controls {
            position: absolute;
            top: 100px;
            right: 15px;
            z-index: 1000;
            background-color: white;
            padding: 1rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }

        .form-check {
            margin-bottom: 0.5rem;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.5rem 1rem;
            font-weight: 500;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
        }

        /* Estilo para o botão de encolher/expandir filtros */
        .filter-collapse-btn {
            position: absolute;
            right: 10px;
            top: 10px;
            z-index: 10;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            border: none;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .filter-collapse-btn:hover {
            transform: scale(1.1);
        }
        
        .map-sidebar.collapsed {
            width: 50px !important;
            overflow: hidden;
            height: auto !important;
            max-height: 50px !important;
        }
        
        .map-sidebar.collapsed .tab-content,
        .map-sidebar.collapsed .nav-tabs,
        .map-sidebar.collapsed .drag-handle {
            visibility: hidden;
            display: none;
        }
        
        .map-sidebar.collapsed .filter-collapse-btn {
            transform: rotate(180deg);
            position: relative;
            top: 5px;
            right: 0;
            margin: 5px auto;
            display: block;
        }
        
        /* Estilos para layout de filtro em tela cheia para dispositivos móveis */
        .fullscreen-filter {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: white;
            z-index: 2000;
            padding: 20px;
            overflow-y: auto;
        }
        
        .fullscreen-filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .fullscreen-filter-content {
            padding-bottom: 20px;
        }
        
        /* Estilos para a barra de arraste */
        .drag-handle {
            width: 50px;
            height: 5px;
            background-color: #ddd;
            border-radius: 10px;
            margin: 10px auto;
            cursor: grab;
            display: none;
        }
        
        /* Estilos para responsividade em dispositivos móveis */
        @media (max-width: 768px) {
            body {
                padding-top: 56px;
            }
            
            .navbar {
                position: fixed;
                top: 0;
                width: 100%;
                z-index: 1002;
            }
            
            /* Novo layout para mobile - filtro antes do mapa */
            #map-container {
                display: flex;
                flex-direction: column;
                position: fixed;
                top: 56px;
                left: 0;
                right: 0;
                bottom: 0;
            }
            
            #filters-mobile {
                display: block;
                background-color: white;
                padding: 15px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                z-index: 1001;
                position: relative; /* Alterado para relative */
                width: 100%;
            }
            
            #map {
                position: relative;
                flex: 1;
                height: calc(100vh - 56px - var(--filters-height, 0px));
                width: 100%;
                top: auto;
                left: auto;
                right: auto;
                bottom: auto;
            }
            
            .map-sidebar {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                height: 40vh;
                max-height: 40vh;
                margin: 0;
                border-radius: 15px 15px 0 0;
                z-index: 1001;
                box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s ease;
                background-color: white;
            }
            
            .drag-handle {
                display: block;
                margin-top: 5px;
                margin-bottom: 5px;
            }
            
            .map-sidebar.minimized {
                transform: translateY(calc(100% - 35px));
                height: auto !important;
            }
            
            .map-sidebar.minimized .tab-content,
            .map-sidebar.minimized .nav-tabs {
                opacity: 0;
            }
            
            .map-sidebar.minimized.filter-collapsed {
                visibility: hidden;
                opacity: 0;
                transform: translateY(100%);
            }
            
            .filter-collapse-btn {
                display: none;
            }
            
            /* Ajuste para os tabs em dispositivos móveis */
            .nav-tabs {
                padding-top: 0;
                margin-top: 0;
            }
            
            /* Reduzir o padding dos itens para economizar espaço */
            .partner-item, .item-card {
                padding: 0.5rem;
                margin-bottom: 0.5rem;
            }
            
            .item-card-header, .item-card-footer {
                padding: 0.5rem;
            }
            
            .item-card-body {
                padding: 0.5rem;
            }
            
            /* Esconder controles de filtro desktop */
            .filter-controls {
                display: none;
            }
        }
        
        @media (max-width: 576px) {
            .map-sidebar {
                max-height: 50vh;
            }
            
            .nav-tabs .nav-link {
                padding: 0.5rem 0.5rem;
                font-size: 0.85rem;
            }
            
            /* Reduzir ainda mais o tamanho dos elementos para telas muito pequenas */
            .item-card-header div, .partner-item h6 {
                font-size: 0.9rem;
            }
            
            .small, .item-badge {
                font-size: 0.75rem;
            }
            
            #filters-mobile .form-check-label {
                font-size: 0.8rem;
            }
        }
        
        /* Estilos para o botão de contrair/expandir filtro em dispositivos móveis */
        .mobile-filter-toggle {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            font-size: 1.4rem;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .mobile-filter-toggle:hover {
            background-color: var(--primary-color-dark);
        }
        
        @media (max-width: 768px) {
            /* Ajustes para o layout móvel */
            .map-sidebar {
                z-index: 1050; /* Aumentar z-index para garantir que fique sobre o mapa */
                transition: transform 0.3s ease;
            }
            
            .mobile-filter-toggle {
                display: flex !important;
            }
            
            .map-sidebar .mobile-filter-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .map-sidebar.filter-collapsed {
                transform: translateY(100%);
                box-shadow: none;
                visibility: hidden;
                opacity: 0;
            }
            
            .map-sidebar.filter-collapsed .tab-content,
            .map-sidebar.filter-collapsed .nav-tabs {
                opacity: 0;
                pointer-events: none;
            }
            
            .map-sidebar .mobile-filter-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('paginaInicial') }}">
                <i class="fas fa-search me-2"></i>
                Ache Aqui CG
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                   
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ auth()->user()->isAdmin() ? route('admin.principal') : (auth()->user()->isParceiro() ? route('parceiro.home') : route('usuario.home')) }}">Minha Conta</a>
                        </li>
                    @else
                        <li class="nav-item">
                             <a class="btn btn-outline-primary" href="{{ route('form.login') }}">
                            Login
                                </a>
                        </li>
                        
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Filtros para mobile removidos para evitar duplicação -->

    <!-- Container principal para o mapa -->
    <div id="map-container">
        <!-- Mapa -->
        <div id="map"></div>
    </div>
    
    <!-- Botão flutuante para contrair/expandir filtro em dispositivos móveis -->
    <button class="mobile-filter-toggle" id="mobileFilterToggle" title="Mostrar/Esconder filtros">
        <i class="fas fa-filter"></i>
    </button>

    <!-- Sidebar para listar parceiros e itens -->
    <div class="map-sidebar" id="mapSidebar">
        <button class="filter-collapse-btn" id="collapseFilterBtn" title="Encolher/Expandir filtros">
            <i class="fas fa-chevron-left"></i>
        </button>
        <div class="drag-handle d-md-none"></div>
        <ul class="nav nav-tabs" id="mapTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="parceiros-tab" data-bs-toggle="tab" data-bs-target="#parceiros-panel" type="button" role="tab" aria-controls="parceiros-panel" aria-selected="true">
                    <i class="fas fa-store me-1"></i> Parceiros
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="itens-tab" data-bs-toggle="tab" data-bs-target="#itens-panel" type="button" role="tab" aria-controls="itens-panel" aria-selected="false">
                    <i class="fas fa-box me-1"></i> Itens
                </button>
            </li>
        </ul>
        
        <div class="tab-content" id="mapTabsContent">
            <!-- Painel de Parceiros -->
            <div id="parceiros-panel" class="tab-pane fade show active" role="tabpanel" aria-labelledby="parceiros-tab">
                <div class="p-3 border-bottom">
                    <div class="input-group mb-2">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" id="search-establishment" class="form-control border-start-0" placeholder="Buscar parceiro...">
                        <button class="btn btn-outline-secondary border-start-0" type="button" id="clear-search-establishment">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                
                <div id="parceiros-lista">
                    <div id="no-partners-results" class="alert alert-info text-center" style="display: none;">
                        <i class="fas fa-info-circle me-2"></i>Nenhum parceiro encontrado
                    </div>
                    @foreach($parceiros as $parceiro)
                        <div class="partner-item" data-id="{{ $parceiro->id }}">
                            <h6 class="mb-1">{{ $parceiro->nome_estabelecimento }}</h6>
                            <p class="mb-1 small text-muted">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ $parceiro->localizacao->endereco }}
                            </p>
                            <span class="partner-type tipo-{{ $parceiro->tipo_parceiro }}">
                                @if($parceiro->tipo_parceiro == 'ponto_coleta')
                                    <i class="fas fa-store me-1"></i>Ponto de Coleta
                                @elseif($parceiro->tipo_parceiro == 'evento')
                                    <i class="fas fa-calendar-alt me-1"></i>Local de Evento
                                @else
                                    <i class="fas fa-store me-1"></i><i class="fas fa-calendar-alt me-1"></i>Ponto de Coleta e Evento
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Painel de Itens -->
            <div id="itens-panel" class="tab-pane fade" role="tabpanel" aria-labelledby="itens-tab">
                <div class="p-3 border-bottom">
                    <div class="input-group mb-2">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" id="search-item" class="form-control border-start-0" placeholder="Buscar item...">
                        <button class="btn btn-outline-secondary border-start-0" type="button" id="clear-search-item">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="filter-achados" checked onchange="filterItems()">
                            <label class="form-check-label small" for="filter-achados">
                                <i class="fas fa-hand-holding text-success me-1"></i>Achados
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="filter-perdidos" checked onchange="filterItems()">
                            <label class="form-check-label small" for="filter-perdidos">
                                <i class="fas fa-search text-warning me-1"></i>Perdidos
                            </label>
                        </div>
                    </div>
                    <div id="filtros-ativos" class="mb-2 d-none">
                        <div class="small text-primary">
                            <i class="fas fa-filter me-1"></i><span id="filtro-info">Filtros aplicados</span>
                        </div>
                    </div>
                    
                    <select id="filter-categoria" class="form-select form-select-sm" onchange="filterItems()">
                        <option value="">Todas as categorias</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nome_categoria }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div id="itens-lista" class="p-2">
                    <div id="no-items-results" class="alert alert-info text-center" style="display: none;">
                        <i class="fas fa-info-circle me-2"></i>Nenhum item encontrado
                    </div>
                    @foreach($itens as $item)
                        <div class="item-card mb-3" data-id="{{ $item->id }}" data-tipo="{{ $item->tipo }}" data-categoria="{{ $item->categoria->id }}" data-titulo="{{ $item->titulo }}" data-categoria-nome="{{ $item->categoria->nome_categoria }}" data-endereco="{{ $item->localizacao ? $item->localizacao->endereco : '' }}" data-descricao="{{ $item->descricao }}">
                            <div class="item-card-header">
                                <div>
                                    {{ Str::limit($item->titulo, 25) }}
                                </div>
                                <span class="item-badge tipo-{{ $item->tipo }}">
                                    @if($item->tipo == 'achado')
                                        <i class="fas fa-hand-holding me-1"></i>Achado
                                    @else
                                        <i class="fas fa-search me-1"></i>Perdido
                                    @endif
                                </span>
                            </div>
                            <div class="item-card-body p-2">
                                <div class="d-flex align-items-center">
                                    <div class="me-2" style="width: 60px; height: 60px; overflow: hidden; border-radius: 4px;">
                                        @if($item->fotos->count() > 0)
                                            <img src="{{ asset('storage/' . $item->fotos->first()->caminho) }}" alt="{{ $item->titulo }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" style="width: 100%; height: 100%;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="small"><i class="fas fa-tag me-1 text-muted"></i>{{ $item->categoria->nome }}</div>
                                        <div class="small"><i class="fas fa-calendar-alt me-1 text-muted"></i>{{ date('d/m/Y', strtotime($item->tipo == 'achado' ? $item->data_encontrado : $item->data_perdido )) }}</div>
                                        @if($item->status == 'em_estabelecimento' && $item->parceiro)
                                            <div class="small"><i class="fas fa-store me-1 text-muted"></i>{{ Str::limit($item->parceiro->nome_estabelecimento, 20) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="item-card-footer text-center">
                                <span class="small text-muted">Faça login para ver detalhes</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        // Definir initMap como uma função global para que o Google Maps possa chamá-la
        window.initMap = function() {
            // Configurar o botão de encolher/expandir filtros
            const collapseFilterBtn = document.getElementById('collapseFilterBtn');
            const mapSidebar = document.getElementById('mapSidebar');
            const dragHandle = document.querySelector('.drag-handle');
            const mobileFilterToggle = document.getElementById('mobileFilterToggle');
            
            // Funcionalidade para encolher/expandir o sidebar em desktops
            if (collapseFilterBtn && mapSidebar) {
                collapseFilterBtn.addEventListener('click', function() {
                    mapSidebar.classList.toggle('collapsed');
                });
            }
            
            // Funcionalidade para dispositivos móveis - minimizar/maximizar o sidebar
            if (dragHandle && mapSidebar) {
                let startY, startHeight;
                
                // Inicializar com o filtro completamente contraído em dispositivos móveis
                if (window.innerWidth <= 768) {
                    // Inicializar com o filtro contraído
                    mapSidebar.classList.add('filter-collapsed');
                    mapSidebar.style.visibility = 'hidden';
                    mapSidebar.style.opacity = '0';
                    mapSidebar.style.transform = 'translateY(100%)';
                    document.documentElement.style.setProperty('--filters-height', '0px');
                    
                    // Atualizar o ícone do botão de filtro
                    if (mobileFilterToggle) {
                        const icon = mobileFilterToggle.querySelector('i');
                        icon.className = 'fas fa-filter';
                    }
                }
                
                // Clicar na barra de arraste alterna entre minimizado e maximizado
                dragHandle.addEventListener('click', function() {
                    mapSidebar.classList.toggle('minimized');
                    
                    // Ajustar a altura do mapa quando o filtro é expandido/minimizado
                    adjustMapHeight();
                });
                
                // Implementação de arraste para a barra
                dragHandle.addEventListener('touchstart', function(e) {
                    startY = e.touches[0].clientY;
                    startHeight = mapSidebar.offsetHeight;
                    
                    document.addEventListener('touchmove', handleTouchMove, { passive: false });
                    document.addEventListener('touchend', handleTouchEnd);
                });
                
                function handleTouchMove(e) {
                    e.preventDefault();
                    const deltaY = startY - e.touches[0].clientY;
                    
                    // Limitar a altura máxima para 40% da altura da tela
                    const maxHeight = window.innerHeight * 0.4;
                    const newHeight = Math.min(Math.max(startHeight + deltaY, 35), maxHeight);
                    
                    mapSidebar.style.height = newHeight + 'px';
                    
                    // Ajustar a altura do mapa em tempo real durante o arraste
                    const mapElement = document.getElementById('map');
                    if (mapElement) {
                        // Não há mais filtros móveis separados
                        mapElement.style.height = `calc(100vh - 56px - ${newHeight}px)`;
                    }
                    
                    // Se o arraste for significativo para baixo, minimizar
                    if (deltaY < -30) {
                        mapSidebar.classList.add('minimized');
                    }
                    // Se o arraste for significativo para cima, maximizar
                    else if (deltaY > 30) {
                        mapSidebar.classList.remove('minimized');
                    }
                }
                
                function handleTouchEnd() {
                    document.removeEventListener('touchmove', handleTouchMove);
                    document.removeEventListener('touchend', handleTouchEnd);
                    
                    // Verificar se o filtro está quase fechado ou quase aberto e ajustar adequadamente
                    const currentHeight = mapSidebar.offsetHeight;
                    const maxHeight = window.innerHeight * 0.4;
                    
                    if (currentHeight < 50) {
                        // Se estiver quase fechado, fechar completamente
                        mapSidebar.classList.add('minimized');
                    } else if (currentHeight > maxHeight * 0.7) {
                        // Se estiver quase aberto, abrir completamente
                        mapSidebar.classList.remove('minimized');
                    }
                    
                    // Resetar altura personalizada após o arraste
                    mapSidebar.style.height = '';
                    
                    // Ajustar a altura do mapa
                    adjustMapHeight();
                }
                
                // Configurar o botão de contrair/expandir filtro em dispositivos móveis
                if (mobileFilterToggle) {
                    mobileFilterToggle.addEventListener('click', function() {
                        if (mapSidebar) {
                            if (mapSidebar.classList.contains('filter-collapsed')) {
                                // Expandir o filtro diretamente para o estado completo
                                mapSidebar.classList.remove('filter-collapsed');
                                mapSidebar.classList.remove('minimized');
                                mapSidebar.style.visibility = 'visible';
                                mapSidebar.style.opacity = '1';
                                mapSidebar.style.transform = 'translateY(0)';
                                this.querySelector('i').className = 'fas fa-times';
                            } else {
                                // Contrair o filtro completamente
                                mapSidebar.classList.add('filter-collapsed');
                                mapSidebar.style.visibility = 'hidden';
                                mapSidebar.style.opacity = '0';
                                mapSidebar.style.transform = 'translateY(100%)';
                                this.querySelector('i').className = 'fas fa-filter';
                            }
                            
                            // Redimensionar o mapa
                            setTimeout(() => {
                                google.maps.event.trigger(map, 'resize');
                                
                                // Ajustar a altura do mapa
                                adjustMapHeight();
                            }, 300);
                        }
                    });
                }
                
                // Função para ajustar o layout do mapa e sidebar
                function adjustMapHeight() {
                    const mapElement = document.getElementById('map');
                    if (mapElement) {
                        if (window.innerWidth <= 768) {
                            // Em dispositivos móveis, usar layout fixo
                            if (mapSidebar.classList.contains('minimized')) {
                                // Quando minimizado, o mapa ocupa quase toda a tela
                                mapElement.style.height = 'calc(100vh - 56px - 35px)';
                            } else {
                                // Quando o sidebar está expandido, ajustar o mapa
                                const sidebarHeight = Math.min(mapSidebar.offsetHeight, window.innerHeight * 0.4);
                                mapElement.style.height = `calc(100vh - 56px - ${sidebarHeight}px)`;
                            }
                        } else {
                            // Em desktops, usar o layout original
                            mapElement.style.position = '';
                            mapElement.style.top = '';
                            mapElement.style.left = '';
                            mapElement.style.right = '';
                            mapElement.style.bottom = '';
                            mapElement.style.height = 'calc(100vh - 56px)';
                        }
                    }
                }
                
                // Ajustar a altura do mapa na inicialização
                adjustMapHeight();
                
                // Ajustar a altura do mapa quando a janela é redimensionada
                window.addEventListener('resize', function() {
                    adjustMapHeight();
                });
            }
            // Coordenadas do centro do mapa (Campo Grande - MS)
            const center = { lat: -20.4697105, lng: -54.620121100000006 };

            // Criar o mapa
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 13,
                center: center,
                mapTypeControl: true,
                streetViewControl: true,
                fullscreenControl: true,
                styles: [
                    {
                        "featureType": "poi",
                        "elementType": "labels",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    }
                ]
            });

            // Parceiros do banco de dados
            const parceiros = @json($parceiros);
            
            // Arrays para armazenar marcadores
            const parceiroMarkers = [];
            const parceiroInfoWindows = [];
            const itemMarkers = [];
            const itemInfoWindows = [];
            
            @foreach($parceiros as $parceiro)
                @if($parceiro->localizacao)
                    const marker{{ $parceiro->id }} = new google.maps.Marker({
                        position: { 
                            lat: {{ $parceiro->localizacao->latitude }}, 
                            lng: {{ $parceiro->localizacao->longitude }} 
                        },
                        map: map,
                        title: "{{ $parceiro->nome_estabelecimento }}",
                        icon: {
                            url: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png"
                        },
                        type: "parceiro",
                        parceiroId: {{ $parceiro->id }},
                        parceiroType: "{{ $parceiro->tipo_parceiro }}"
                    });
                    
                    const infoContent{{ $parceiro->id }} = `
                        <div class="info-window">
                            @if($parceiro->logo)
                                <img src="{{ asset('storage/' . $parceiro->logo) }}" alt="{{ $parceiro->nome_estabelecimento }}" class="img-fluid rounded mb-3" style="width: 100%; height: 150px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded mb-3" style="width: 100%; height: 150px;">
                                    <i class="fas fa-store fa-3x text-muted"></i>
                                </div>
                            @endif
                            <h5>{{ $parceiro->nome_estabelecimento }}</h5>
                            <p><i class="fas fa-map-marker-alt me-2"></i>{{ $parceiro->localizacao->endereco }}</p>
                            <p><i class="fas fa-phone me-2"></i>{{ $parceiro->telefone }}</p>
                            @if($parceiro->horario_funcionamento)
                                <p><i class="fas fa-clock me-2"></i>{{ $parceiro->horario_funcionamento }}</p>
                            @endif
                            <div class="mt-3">
                                <span class="partner-type tipo-{{ $parceiro->tipo_parceiro }}">
                                    @if($parceiro->tipo_parceiro == 'ponto_coleta')
                                        <i class="fas fa-store me-1"></i>Ponto de Coleta
                                    @elseif($parceiro->tipo_parceiro == 'evento')
                                        <i class="fas fa-calendar-alt me-1"></i>Local de Evento
                                    @else
                                        <i class="fas fa-store me-1"></i><i class="fas fa-calendar-alt me-1"></i>Ponto de Coleta e Evento
                                    @endif
                                </span>
                            </div>
                        </div>
                    `;
                    
                    const infoWindow{{ $parceiro->id }} = new google.maps.InfoWindow({
                        content: infoContent{{ $parceiro->id }}
                    });
                    
                    marker{{ $parceiro->id }}.addListener('click', () => {
                        parceiroInfoWindows.forEach(info => info.close());
                        itemInfoWindows.forEach(info => info.close());
                        
                        infoWindow{{ $parceiro->id }}.open(map, marker{{ $parceiro->id }});
                    });
                    
                    parceiroMarkers.push(marker{{ $parceiro->id }});
                    parceiroInfoWindows.push(infoWindow{{ $parceiro->id }});
                @endif
            @endforeach
            
            @foreach($itens as $item)
                @if($item->localizacao)
                    // Definir ícone baseado no tipo do item
                    const iconUrl{{ $item->id }} = '{{ $item->tipo }}' === 'achado' 
                        ? 'https://maps.google.com/mapfiles/ms/icons/green-dot.png'
                        : 'https://maps.google.com/mapfiles/ms/icons/yellow-dot.png';
                    
                    const itemMarker{{ $item->id }} = new google.maps.Marker({
                        position: { 
                            lat: {{ $item->localizacao->latitude }}, 
                            lng: {{ $item->localizacao->longitude }} 
                        },
                        map: map,
                        title: "{{ $item->titulo }}",
                        icon: {
                            url: iconUrl{{ $item->id }}
                        },
                        type: "item",
                        itemId: {{ $item->id }},
                        itemType: "{{ $item->tipo }}",
                        categoriaId: {{ $item->categoria->id }},
                        status: "{{ $item->status }}"
                    });
                    
                    const itemInfoContent{{ $item->id }} = `
                        <div class="info-window">
                            <h5>{{ $item->titulo }}</h5>
                            @if($item->fotos->count() > 0)
                                <img src="{{ asset('storage/' . $item->fotos->first()->caminho) }}" alt="{{ $item->titulo }}" class="img-fluid mb-2">
                            @endif
                            <p><i class="fas fa-tag me-2"></i>{{ $item->categoria->nome_categoria }}</p>
                            <p><i class="fas fa-calendar-alt me-2"></i>{{ date('d/m/Y', strtotime($item->tipo == 'achado' ? $item->data_encontrado : $item->data_perdido )) }}</p>
                            <p><i class="fas fa-map-marker-alt me-2"></i>{{ $item->localizacao->endereco }}</p>
                            @if($item->status == 'em_estabelecimento' && $item->parceiro)
                                <p><i class="fas fa-store me-2"></i>{{ $item->parceiro->nome_estabelecimento }}</p>
                            @endif
                            <div class="mt-3">
                                <span class="item-badge tipo-{{ $item->tipo }}">
                                    @if($item->tipo == 'achado')
                                        <i class="fas fa-hand-holding me-1"></i>Achado
                                    @else
                                        <i class="fas fa-search me-1"></i>Perdido
                                    @endif
                                </span>
                            </div>
                            <div class="mt-3 text-center">
                                <small class="text-muted">Faça login para ver mais detalhes</small>
                            </div>
                        </div>
                    `;
                    
                    const itemInfoWindow{{ $item->id }} = new google.maps.InfoWindow({
                        content: itemInfoContent{{ $item->id }}
                    });
                    
                    itemMarker{{ $item->id }}.addListener('click', () => {
                        parceiroInfoWindows.forEach(info => info.close());
                        itemInfoWindows.forEach(info => info.close());
                        
                        itemInfoWindow{{ $item->id }}.open(map, itemMarker{{ $item->id }});
                    });
                    
                    itemMarkers.push(itemMarker{{ $item->id }});
                    itemInfoWindows.push(itemInfoWindow{{ $item->id }});
                @endif
            @endforeach
            
            document.querySelectorAll('.partner-item').forEach(item => {
                item.addEventListener('click', function() {
                    // Remover classe active de todos os parceiros
                    document.querySelectorAll('.partner-item').forEach(i => i.classList.remove('active'));
                    // Adicionar classe active ao parceiro clicado
                    this.classList.add('active');
                    
                    const id = this.dataset.id;
                    
                    // Encontrar o marcador correspondente pelo ID em vez do título
                    const marker = parceiroMarkers.find(m => m.parceiroId === parseInt(id));
                    
                    if (marker) {
                        // Centralizar no marcador com animação suave
                        map.panTo(marker.getPosition());
                        map.setZoom(15);
                        
                        // Fechar todas as janelas de informação abertas
                        parceiroInfoWindows.forEach(info => info.close());
                        itemInfoWindows.forEach(info => info.close());
                        
                        // Abrir a janela de informação do parceiro clicado
                        const index = parceiroMarkers.indexOf(marker);
                        if (index >= 0) {
                            // Pequeno atraso para garantir que a centralização ocorra antes
                            setTimeout(() => {
                                parceiroInfoWindows[index].open(map, marker);
                                // Destacar o marcador com uma animação
                                marker.setAnimation(google.maps.Animation.BOUNCE);
                                setTimeout(() => marker.setAnimation(null), 1500);
                            }, 300);
                        }
                    } else {
                        console.log('Marcador não encontrado para o parceiro ID:', id);
                    }
                });
            });
            
            document.querySelectorAll('.item-card').forEach(item => {
                item.addEventListener('click', function() {
                    // Remover classe active de todos os itens
                    document.querySelectorAll('.item-card').forEach(i => i.classList.remove('active'));
                    // Adicionar classe active ao item clicado
                    this.classList.add('active');
                    
                    const id = this.dataset.id;
                    
                    // Encontrar o marcador correspondente pelo ID em vez do título
                    const marker = itemMarkers.find(m => m.itemId === parseInt(id));
                    
                    if (marker) {
                        // Centralizar no marcador com animação suave
                        map.panTo(marker.getPosition());
                        map.setZoom(15);
                        
                        // Fechar todas as janelas de informação abertas
                        parceiroInfoWindows.forEach(info => info.close());
                        itemInfoWindows.forEach(info => info.close());
                        
                        // Abrir a janela de informação do item clicado
                        const index = itemMarkers.indexOf(marker);
                        if (index >= 0) {
                            // Pequeno atraso para garantir que a centralização ocorra antes
                            setTimeout(() => {
                                itemInfoWindows[index].open(map, marker);
                                // Destacar o marcador com uma animação
                                marker.setAnimation(google.maps.Animation.BOUNCE);
                                setTimeout(() => marker.setAnimation(null), 1500);
                            }, 300);
                        }
                    } else {
                        console.log('Marcador não encontrado para o item ID:', id);
                    }
                });
            });
            
            // Adicionar evento ao campo de busca de estabelecimentos se existir
            const searchEstablishment = document.getElementById('search-establishment');
            const clearSearchEstablishment = document.getElementById('clear-search-establishment');
            
            if (searchEstablishment) {
                searchEstablishment.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();
                    
                    // Filtrar marcadores de parceiros
                    parceiroMarkers.forEach(marker => {
                        const markerTitle = marker.getTitle().toLowerCase();
                        const shouldShow = markerTitle.includes(searchTerm);
                        marker.setVisible(shouldShow);
                    });
                    
                    // Filtrar itens da lista de parceiros
                    let visibleCount = 0;
                    document.querySelectorAll('.partner-item').forEach(item => {
                        const h6Element = item.querySelector('h6');
                        if (h6Element) {
                            const establishmentName = h6Element.textContent.toLowerCase();
                            const shouldShow = establishmentName.includes(searchTerm);
                            item.style.display = shouldShow ? 'block' : 'none';
                            if (shouldShow) visibleCount++;
                        }
                    });
                    
                    // Mostrar mensagem se nenhum parceiro for encontrado
                    const noResultsMessage = document.getElementById('no-partners-results');
                    if (noResultsMessage) {
                        noResultsMessage.style.display = visibleCount === 0 ? 'block' : 'none';
                    }
                    
                    // Mostrar ou esconder o botão de limpar
                    if (clearSearchEstablishment) {
                        clearSearchEstablishment.style.display = this.value.trim() !== '' ? 'block' : 'none';
                    }
                });
            }
            
            if (clearSearchEstablishment) {
                // Inicialmente esconder o botão de limpar
                clearSearchEstablishment.style.display = 'none';
                
                clearSearchEstablishment.addEventListener('click', function() {
                    if (searchEstablishment) {
                        searchEstablishment.value = '';
                        searchEstablishment.dispatchEvent(new Event('input'));
                        this.style.display = 'none';
                    }
                });
            }
            
            // Adicionar evento ao campo de busca de itens se existir
            const searchItem = document.getElementById('search-item');
            const clearSearchItem = document.getElementById('clear-search-item');
            
            if (searchItem) {
                searchItem.addEventListener('input', function() {
                    filterItems();
                    // Mostrar ou esconder o botão de limpar
                    if (clearSearchItem) {
                        clearSearchItem.style.display = this.value.trim() !== '' ? 'block' : 'none';
                    }
                });
            }
            
            if (clearSearchItem) {
                // Inicialmente esconder o botão de limpar
                clearSearchItem.style.display = 'none';
                
                clearSearchItem.addEventListener('click', function() {
                    if (searchItem) {
                        searchItem.value = '';
                        filterItems();
                        this.style.display = 'none';
                    }
                });
            }
            
            // Adicionar eventos aos filtros de itens se existirem
            const filterAchados = document.getElementById('filter-achados');
            const filterPerdidos = document.getElementById('filter-perdidos');
            const filterCategoria = document.getElementById('filter-categoria');
            
            if (filterAchados) {
                filterAchados.addEventListener('change', filterItems);
            }
            
            if (filterPerdidos) {
                filterPerdidos.addEventListener('change', filterItems);
            }
            
            if (filterCategoria) {
                filterCategoria.addEventListener('change', filterItems);
            }
            
            function filterItems() {
                console.log('Aplicando filtros...');
                const searchItem = document.getElementById('search-item');
                const filterAchados = document.getElementById('filter-achados');
                const filterPerdidos = document.getElementById('filter-perdidos');
                const filterCategoria = document.getElementById('filter-categoria');
                const filtrosAtivos = document.getElementById('filtros-ativos');
                const filtroInfo = document.getElementById('filtro-info');
                
                // Obter o valor do campo de busca
                const searchTerm = searchItem ? searchItem.value : '';
                const searchTermLower = searchTerm.toLowerCase().trim();
                console.log('Termo de busca:', searchTermLower);
                
                // Obter o estado dos checkboxes
                const showAchados = filterAchados ? filterAchados.checked : true;
                const showPerdidos = filterPerdidos ? filterPerdidos.checked : true;
                console.log('Mostrar achados:', showAchados, 'Mostrar perdidos:', showPerdidos);
                
                // Obter o valor do select de categoria
                const categoriaId = filterCategoria ? filterCategoria.value : '';
                console.log('Categoria ID:', categoriaId);
                
                // Verificar se algum filtro está ativo
                const hasActiveFilters = searchTermLower !== '' || !showAchados || !showPerdidos || categoriaId !== '';
                
                // Atualizar a UI para mostrar filtros ativos
                if (filtrosAtivos) {
                    filtrosAtivos.classList.toggle('d-none', !hasActiveFilters);
                    
                    if (hasActiveFilters && filtroInfo) {
                        let filterText = [];
                        
                        if (searchTermLower !== '') {
                            filterText.push(`Busca: "${searchTermLower}"`); 
                        }
                        
                        if (!showAchados || !showPerdidos) {
                            let tiposAtivos = [];
                            if (showAchados) tiposAtivos.push('Achados');
                            if (showPerdidos) tiposAtivos.push('Perdidos');
                            filterText.push(`Tipo: ${tiposAtivos.join(', ')}`);
                        }
                        
                        if (categoriaId !== '' && filterCategoria) {
                            const categoriaSelecionada = filterCategoria.options[filterCategoria.selectedIndex].text;
                            filterText.push(`Categoria: ${categoriaSelecionada}`);
                        }
                        
                        filtroInfo.textContent = filterText.join(' | ');
                    }
                }
                
                // Destacar visualmente o select de categoria se estiver filtrado
                if (filterCategoria) {
                    if (categoriaId !== '') {
                        filterCategoria.classList.add('border-primary');
                    } else {
                        filterCategoria.classList.remove('border-primary');
                    }
                }
                
                // Contar itens visíveis
                let visibleCount = 0;
                
                // Função auxiliar para verificar se um termo de busca corresponde a qualquer campo relevante
                function matchesSearchTerm(marker, term) {
                    if (term === '') return true;
                    
                    // Dividir o termo de busca em palavras individuais para busca mais flexível
                    const searchWords = term.split(/\s+/).filter(word => word.length > 0);
                    
                    // Se não houver palavras válidas, retornar true
                    if (searchWords.length === 0) return true;
                    
                    // Encontrar o item correspondente ao marcador
                    const itemElement = document.querySelector(`.item-card[data-id="${marker.itemId}"]`);
                    
                    // Obter todos os campos relevantes para busca
                    const fieldsToSearch = [
                        marker.getTitle().toLowerCase() // título
                    ];
                    
                    // Adicionar campos do elemento do item, se encontrado
                    if (itemElement) {
                        fieldsToSearch.push(
                            itemElement.dataset.categoriaNome?.toLowerCase() || '', // nome da categoria
                            itemElement.dataset.endereco?.toLowerCase() || '', // endereço
                            itemElement.dataset.descricao?.toLowerCase() || '' // descrição
                        );
                    }
                    
                    // Verificar se todas as palavras da busca estão em pelo menos um dos campos
                    const matches = searchWords.every(word => {
                        return fieldsToSearch.some(field => field.includes(word));
                    });
                    
                    console.log('Verificando marcador:', marker.getTitle(), 'Termo:', term, 'Resultado:', matches);
                    return matches;
                }
                
                // Filtrar marcadores no mapa
                itemMarkers.forEach(marker => {
                    const matchesSearch = matchesSearchTerm(marker, searchTermLower);
                    const matchesTipo = (marker.itemType === 'achado' && showAchados) || (marker.itemType === 'perdido' && showPerdidos);
                    const matchesCategoria = categoriaId === '' || marker.categoriaId.toString() === categoriaId;
                    
                    const isVisible = matchesSearch && matchesTipo && matchesCategoria;
                    console.log('Marcador:', marker.getTitle(), 'Tipo:', marker.itemType, 'Visível:', isVisible);
                    marker.setVisible(isVisible);
                });
                
                // Filtrar itens na lista
                document.querySelectorAll('.item-card').forEach(item => {
                    const itemTipo = item.dataset.tipo;
                    const itemCategoria = item.dataset.categoria;
                    const itemTitulo = item.dataset.titulo?.toLowerCase() || '';
                    const itemCategoriaNome = item.dataset.categoriaNome?.toLowerCase() || '';
                    const itemEndereco = item.dataset.endereco?.toLowerCase() || '';
                    const itemDescricao = item.dataset.descricao?.toLowerCase() || '';
                    
                    // Verificar se o termo de busca corresponde a qualquer campo relevante
                    let matchesSearch = true;
                    if (searchTermLower !== '') {
                        // Dividir o termo de busca em palavras individuais
                        const searchWords = searchTermLower.split(/\s+/).filter(word => word.length > 0);
                        // Verificar se todas as palavras da busca estão em pelo menos um dos campos
                        matchesSearch = searchWords.every(word => {
                            return itemTitulo.includes(word) || 
                                   itemCategoriaNome.includes(word) || 
                                   itemEndereco.includes(word) || 
                                   itemDescricao.includes(word);
                        });
                    }
                    
                    const matchesTipo = (itemTipo === 'achado' && showAchados) || (itemTipo === 'perdido' && showPerdidos);
                    const matchesCategoria = categoriaId === '' || itemCategoria === categoriaId;
                    
                    const isVisible = matchesSearch && matchesTipo && matchesCategoria;
                    console.log('Item:', itemTitulo, 'Tipo:', itemTipo, 'Visível:', isVisible);
                    item.style.display = isVisible ? 'block' : 'none';
                    
                    if (isVisible) visibleCount++;
                });
                
                // Mostrar mensagem se nenhum item for encontrado
                const noResultsMessage = document.getElementById('no-items-results');
                if (noResultsMessage) {
                    noResultsMessage.style.display = visibleCount === 0 ? 'block' : 'none';
                }
            }
            
            // Adicionar eventos aos filtros de parceiros se existirem
            const filterPontoColeta = document.getElementById('filter-ponto-coleta');
            const filterEvento = document.getElementById('filter-evento');
            
            if (filterPontoColeta) {
                filterPontoColeta.addEventListener('change', filterParceiros);
            }
            
            if (filterEvento) {
                filterEvento.addEventListener('change', filterParceiros);
            }
            
            function filterParceiros() {
                const filterPontoColeta = document.getElementById('filter-ponto-coleta');
                const filterEvento = document.getElementById('filter-evento');
                
                const showPontoColeta = filterPontoColeta ? filterPontoColeta.checked : true;
                const showEvento = filterEvento ? filterEvento.checked : true;
                const searchTerm = searchEstablishment ? searchEstablishment.value.toLowerCase().trim() : '';
                
                parceiroMarkers.forEach(marker => {
                    const markerTitle = marker.getTitle().toLowerCase();
                    const matchesSearch = searchTerm === '' || markerTitle.includes(searchTerm);
                    
                    if (marker.parceiroType === 'ponto_coleta' && showPontoColeta && matchesSearch) {
                        marker.setVisible(true);
                    } else if (marker.parceiroType === 'evento' && showEvento && matchesSearch) {
                        marker.setVisible(true);
                    } else if (marker.parceiroType === 'ambos' && (showPontoColeta || showEvento) && matchesSearch) {
                        marker.setVisible(true);
                    } else {
                        marker.setVisible(false);
                    }
                });
                
                document.querySelectorAll('.partner-item').forEach(item => {
                    const tipo = item.querySelector('.partner-type').className;
                    const establishmentName = item.querySelector('h6').textContent.toLowerCase();
                    const matchesSearch = searchTerm === '' || establishmentName.includes(searchTerm);
                    
                    if (((tipo.includes('tipo-ponto-coleta') && showPontoColeta) || 
                         (tipo.includes('tipo-evento') && showEvento) || 
                         (tipo.includes('tipo-ambos') && (showPontoColeta || showEvento))) && 
                        matchesSearch) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }
            
            const legendDiv = document.createElement('div');
            legendDiv.className = 'card shadow-sm p-2';
            legendDiv.style.position = 'absolute';
            legendDiv.style.bottom = '20px';
            legendDiv.style.right = '20px';
            legendDiv.style.zIndex = '1';
            legendDiv.style.backgroundColor = 'white';
            legendDiv.style.borderRadius = '8px';
            legendDiv.style.fontSize = '12px';
            
            legendDiv.innerHTML = `
                <div class="d-flex align-items-center mb-1">
                    <img src="https://maps.google.com/mapfiles/ms/icons/blue-dot.png" width="20" height="20" class="me-2">
                    <span>Parceiros</span>
                </div>
                <div class="d-flex align-items-center mb-1">
                    <img src="https://maps.google.com/mapfiles/ms/icons/green-dot.png" width="20" height="20" class="me-2">
                    <span>Itens Achados</span>
                </div>
                <div class="d-flex align-items-center">
                    <img src="https://maps.google.com/mapfiles/ms/icons/yellow-dot.png" width="20" height="20" class="me-2">
                    <span>Itens Perdidos</span>
                </div>
            `;
            
            map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legendDiv);
        }
    </script>

    <!-- Carrega a API do Google Maps -->
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap"
        async
        defer
    ></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>