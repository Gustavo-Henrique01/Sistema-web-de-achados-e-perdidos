<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Parceiros - Achados e Perdidos</title>
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
        
        .info-window {
            max-width: 300px;
            padding: 1rem;
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

        @media (max-width: 768px) {
            .map-sidebar {
                width: calc(100% - 30px);
                position: relative;
                top: 0;
                left: 0;
                margin: 15px;
                max-height: 300px;
            }

            .filter-controls {
                position: relative;
                top: 0;
                right: 0;
                margin: 15px;
                width: calc(100% - 30px);
            }

            #map {
                height: 60vh;
                margin-top: 15px;
            }

            .container-fluid {
                padding: 0 15px;
            }
        }

        @media (max-width: 576px) {
            .map-sidebar {
                max-height: 250px;
            }

            .filter-controls {
                margin-top: 10px;
            }

            #map {
                height: 50vh;
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
                Achados e Perdidos CG
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('paginaInicial') }}">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('mapa') }}">Mapa de Itens</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('parceiros.mapa') }}">Pontos de Coleta</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ auth()->user()->isAdmin() ? route('admin.principal') : (auth()->user()->isParceiro() ? route('parceiro.home') : route('usuario.home')) }}">Minha Conta</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('form.login') }}">Entrar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-primary" href="{{ route('registrar') }}">Cadastrar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar para listar parceiros -->
    <div class="map-sidebar">
        <div class="p-3 bg-primary text-white rounded-top">
            <h5 class="mb-0">
                <i class="fas fa-store me-2"></i>
                Pontos de Coleta
            </h5>
        </div>
        <div class="p-3 border-bottom">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" id="search-establishment" class="form-control border-start-0" placeholder="Buscar estabelecimento...">
            </div>
        </div>
        <div id="parceiros-lista">
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

    <!-- Filtros -->
    <div class="filter-controls">
        <h6 class="mb-3">
            <i class="fas fa-filter me-2"></i>
            Filtrar por Tipo
        </h6>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="filter-ponto-coleta" checked>
            <label class="form-check-label" for="filter-ponto-coleta">
                <i class="fas fa-store me-1"></i>
                Pontos de Coleta
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="filter-evento" checked>
            <label class="form-check-label" for="filter-evento">
                <i class="fas fa-calendar-alt me-1"></i>
                Locais de Eventos
            </label>
        </div>
    </div>

    <!-- Mapa -->
    <div id="map"></div>

    <script>
        // Função para inicializar o mapa
        function initMap() {
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
            
            // Marcadores e infoWindows
            const markers = [];
            const infoWindows = [];
            
            // Criar marcadores para cada parceiro
            parceiros.forEach(parceiro => {
                if (!parceiro.localizacao || !parceiro.localizacao.latitude || !parceiro.localizacao.longitude) {
                    return;
                }
                
                // Definir ícone baseado no tipo de parceiro
                let icon = '';
                if (parceiro.tipo_parceiro === 'ponto_coleta') {
                    icon = 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png';
                } else if (parceiro.tipo_parceiro === 'evento') {
                    icon = 'https://maps.google.com/mapfiles/ms/icons/yellow-dot.png';
                } else {
                    icon = 'https://maps.google.com/mapfiles/ms/icons/green-dot.png';
                }
                
                // Criar marcador
                const marker = new google.maps.Marker({
                    position: { 
                        lat: parseFloat(parceiro.localizacao.latitude), 
                        lng: parseFloat(parceiro.localizacao.longitude) 
                    },
                    map: map,
                    title: parceiro.nome_estabelecimento,
                    icon: icon,
                    type: parceiro.tipo_parceiro
                });
                
                // Conteúdo do infoWindow
                const contentString = `
                    <div class="info-window">
                        ${parceiro.logo ? `<img src="{{ asset('storage/') }}/${parceiro.logo}" alt="${parceiro.nome_estabelecimento}">` : ''}
                        <h5>${parceiro.nome_estabelecimento}</h5>
                        <p class="mb-1">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            ${parceiro.localizacao.endereco}
                        </p>
                        <p class="mb-1">
                            <i class="fas fa-clock me-1"></i>
                            ${parceiro.horario_funcionamento || 'Não informado'}
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-phone me-1"></i>
                            ${parceiro.telefone_comercial || 'Não informado'}
                        </p>
                        
                    </div>
                `;
                
                // Criar infoWindow
                const infoWindow = new google.maps.InfoWindow({
                    content: contentString,
                    maxWidth: 300
                });
                
                // Evento de clique no marcador
                marker.addListener("click", () => {
                    // Fechar todas as infoWindows abertas
                    infoWindows.forEach(info => info.close());
                    
                    // Abrir infoWindow atual
                    infoWindow.open(map, marker);
                });
                
                // Adicionar marcador e infoWindow aos arrays
                markers.push(marker);
                infoWindows.push(infoWindow);
                
                // Associar parceiro ao marcador para referência
                marker.parceiroId = parceiro.id;
            });
            
            // Evento de clique nos itens da lista
            document.querySelectorAll('.partner-item').forEach(item => {
                item.addEventListener('click', function() {
                    const parceiroId = this.dataset.id;
                    
                    // Encontrar o marcador correspondente
                    const marker = markers.find(m => m.parceiroId == parceiroId);
                    if (marker) {
                        // Centralizar o mapa no marcador
                        map.setCenter(marker.getPosition());
                        map.setZoom(15);
                        
                        // Fechar todas as infoWindows abertas
                        infoWindows.forEach(info => info.close());
                        
                        // Abrir infoWindow do marcador
                        const index = markers.indexOf(marker);
                        if (index >= 0) {
                            infoWindows[index].open(map, marker);
                        }
                    }
                });
            });
            
            // Evento de busca por nome do estabelecimento
            const searchInput = document.getElementById('search-establishment');
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                
                // Filtrar marcadores
                markers.forEach(marker => {
                    const markerTitle = marker.getTitle().toLowerCase();
                    const shouldShow = markerTitle.includes(searchTerm);
                    marker.setVisible(shouldShow);
                });
                
                // Filtrar itens da lista
                document.querySelectorAll('.partner-item').forEach(item => {
                    const establishmentName = item.querySelector('h6').textContent.toLowerCase();
                    const shouldShow = establishmentName.includes(searchTerm);
                    item.style.display = shouldShow ? 'block' : 'none';
                });
            });

            // Filtros
            document.getElementById('filter-ponto-coleta').addEventListener('change', updateFilters);
            document.getElementById('filter-evento').addEventListener('change', updateFilters);
            
            function updateFilters() {
                const showPontoColeta = document.getElementById('filter-ponto-coleta').checked;
                const showEvento = document.getElementById('filter-evento').checked;
                const searchTerm = searchInput.value.toLowerCase().trim();
                
                markers.forEach(marker => {
                    const markerTitle = marker.getTitle().toLowerCase();
                    const matchesSearch = markerTitle.includes(searchTerm);
                    
                    if (marker.type === 'ponto_coleta' && showPontoColeta && matchesSearch) {
                        marker.setVisible(true);
                    } else if (marker.type === 'evento' && showEvento && matchesSearch) {
                        marker.setVisible(true);
                    } else if (marker.type === 'ambos' && (showPontoColeta || showEvento) && matchesSearch) {
                        marker.setVisible(true);
                    } else {
                        marker.setVisible(false);
                    }
                });
                
                // Atualizar a lista de parceiros também
                document.querySelectorAll('.partner-item').forEach(item => {
                    const tipo = item.querySelector('.partner-type').className;
                    const establishmentName = item.querySelector('h6').textContent.toLowerCase();
                    const matchesSearch = establishmentName.includes(searchTerm);
                    
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