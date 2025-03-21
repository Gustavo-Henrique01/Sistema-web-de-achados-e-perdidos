<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Parceiros - Achados e Perdidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            padding-top: 56px;
        }
        
        #map {
            height: calc(100vh - 56px);
            width: 100%;
        }
        
        .map-sidebar {
            position: absolute;
            top: 70px;
            left: 15px;
            width: 300px;
            z-index: 1000;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-height: calc(100vh - 100px);
            overflow-y: auto;
        }
        
        .partner-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .partner-item:hover {
            background-color: #f8f9fa;
        }
        
        .partner-type {
            font-size: 12px;
            padding: 3px 8px;
            border-radius: 10px;
            display: inline-block;
            margin-top: 5px;
        }
        
        .tipo-ponto-coleta {
            background-color: #e3f2fd;
            color: #0d6efd;
        }
        
        .tipo-evento {
            background-color: #fff3cd;
            color: #ffc107;
        }
        
        .tipo-ambos {
            background-color: #d1e7dd;
            color: #198754;
        }
        
        .info-window {
            max-width: 300px;
        }
        
        .info-window img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
            float: left;
            margin-right: 10px;
        }
        
        .filter-controls {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 1000;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 10px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('paginaInicial') }}">
                <i class="fas fa-map-marker-alt me-2"></i>
                Achados e Perdidos
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
                            <a class="nav-link" href="{{ route('form.login') }}">Entrar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar para listar parceiros -->
    <div class="map-sidebar">
        <div class="p-3 bg-primary text-white">
            <h5 class="mb-0">Parceiros</h5>
        </div>
        <div id="parceiros-lista">
            @foreach($parceiros as $parceiro)
                <div class="partner-item" data-id="{{ $parceiro->id }}">
                    <h6>{{ $parceiro->nome_estabelecimento }}</h6>
                    <p class="mb-1 small">{{ $parceiro->localizacao->endereco }}</p>
                    <span class="partner-type tipo-{{ $parceiro->tipo_parceiro }}">
                        @if($parceiro->tipo_parceiro == 'ponto_coleta')
                            Ponto de Coleta
                        @elseif($parceiro->tipo_parceiro == 'evento')
                            Local de Evento
                        @else
                            Ponto de Coleta e Evento
                        @endif
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Filtros -->
    <div class="filter-controls">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="filter-ponto-coleta" checked>
            <label class="form-check-label" for="filter-ponto-coleta">Pontos de Coleta</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="filter-evento" checked>
            <label class="form-check-label" for="filter-evento">Locais de Eventos</label>
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
                        ${parceiro.logo ? `<img src="${parceiro.logo}" alt="${parceiro.nome_estabelecimento}">` : ''}
                        <h5>${parceiro.nome_estabelecimento}</h5>
                        <p><strong>Endereço:</strong> ${parceiro.localizacao.endereco}</p>
                        <p><strong>Horário:</strong> ${parceiro.horario_funcionamento || 'Não informado'}</p>
                        <p><strong>Telefone:</strong> ${parceiro.telefone_comercial || 'Não informado'}</p>
                        ${parceiro.tipo_parceiro === 'ponto_coleta' || parceiro.tipo_parceiro === 'ambos' 
                            ? `<a href="#" class="btn btn-sm btn-primary">Ver Itens no Local</a>` 
                            : ''}
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
            
            // Filtros
            document.getElementById('filter-ponto-coleta').addEventListener('change', updateFilters);
            document.getElementById('filter-evento').addEventListener('change', updateFilters);
            
            function updateFilters() {
                const showPontoColeta = document.getElementById('filter-ponto-coleta').checked;
                const showEvento = document.getElementById('filter-evento').checked;
                
                markers.forEach(marker => {
                    if (marker.type === 'ponto_coleta' && showPontoColeta) {
                        marker.setVisible(true);
                    } else if (marker.type === 'evento' && showEvento) {
                        marker.setVisible(true);
                    } else if (marker.type === 'ambos' && (showPontoColeta || showEvento)) {
                        marker.setVisible(true);
                    } else {
                        marker.setVisible(false);
                    }
                });
                
                // Atualizar a lista de parceiros também
                document.querySelectorAll('.partner-item').forEach(item => {
                    const tipo = item.querySelector('.partner-type').className;
                    if ((tipo.includes('tipo-ponto-coleta') && showPontoColeta) || 
                        (tipo.includes('tipo-evento') && showEvento) || 
                        (tipo.includes('tipo-ambos') && (showPontoColeta || showEvento))) {
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