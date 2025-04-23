@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-12">
            <div class="position-relative">
                <!-- Mapa -->
                <div id="map" style="height: 80vh; width: 100%;"></div>
                
                <!-- Painel de Controles -->
                <div class="position-absolute top-0 end-0 m-3 d-none d-md-block">
                    <div class="card shadow-lg border-0" style="width: 280px;">
                        <div class="card-header bg-primary text-white py-2 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 d-flex align-items-center">
                                <i class="fas fa-sliders-h me-2"></i>Filtros
                            </h6>
                            <button class="btn btn-sm btn-light toggle-panel" data-target="filter-panel">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <div id="filter-panel" class="card-body p-3">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Tipo</label>
                                <div class="btn-group w-100 shadow-sm" role="group">
                                    <input type="radio" class="btn-check" name="tipo" id="tipo_todos" value="" checked>
                                    <label class="btn btn-outline-secondary btn-sm" for="tipo_todos">
                                        <i class="fas fa-layer-group me-1"></i>Todos
                                    </label>
                                    
                                    <input type="radio" class="btn-check" name="tipo" id="tipo_achados" value="achado">
                                    <label class="btn btn-outline-success btn-sm" for="tipo_achados">
                                        <i class="fas fa-hand-holding me-1"></i>Achados
                                    </label>
                                    
                                    <input type="radio" class="btn-check" name="tipo" id="tipo_perdidos" value="perdido">
                                    <label class="btn btn-outline-warning btn-sm" for="tipo_perdidos">
                                        <i class="fas fa-search me-1"></i>Perdidos
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Status</label>
                                <div class="btn-group w-100 shadow-sm" role="group">
                                    <input type="radio" class="btn-check" name="status" id="status_todos" value="" checked>
                                    <label class="btn btn-outline-secondary btn-sm" for="status_todos">
                                        <i class="fas fa-bars me-1"></i>Todos
                                    </label>
                                    
                                    <input type="radio" class="btn-check" name="status" id="status_aprovado" value="aprovado">
                                    <label class="btn btn-outline-success btn-sm" for="status_aprovado">
                                        <i class="fas fa-check-circle me-1"></i>Aprovados
                                    </label>
                                    
                                    <input type="radio" class="btn-check" name="status" id="status_estabelecimento" value="em_estabelecimento">
                                    <label class="btn btn-outline-info btn-sm" for="status_estabelecimento">
                                        <i class="fas fa-store me-1"></i>Estabelecimentos Parceiros
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Categoria</label>
                                <div class="input-group input-group-sm shadow-sm">
                                    <span class="input-group-text bg-white"><i class="fas fa-tag text-muted"></i></span>
                                    <select class="form-select" id="categoria">
                                        <option value="">Todas as categorias</option>
                                        @foreach($categorias as $categoria)
                                            <option value="{{ $categoria->id }}">{{ $categoria->nome_categoria }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Data</label>
                                <div class="input-group input-group-sm shadow-sm">
                                    <span class="input-group-text bg-white"><i class="fas fa-calendar text-muted"></i></span>
                                    <input type="date" class="form-control" id="data">
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary btn-sm shadow-sm" onclick="aplicarFiltros()">
                                    <i class="fas fa-filter me-1"></i>Aplicar Filtros
                                </button>
                                
                                <button class="btn btn-outline-secondary btn-sm shadow-sm" onclick="resetarFiltros()">
                                    <i class="fas fa-undo me-1"></i>Limpar Filtros
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Legenda -->
                <div class="position-absolute top-0 start-0 m-3 d-none d-md-block">
                    <div class="card shadow-lg border-0" style="width: 180px;">
                        <div class="card-header bg-primary text-white py-2 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 d-flex align-items-center">
                                <i class="fas fa-info-circle me-2"></i>Legenda
                            </h6>
                            <button class="btn btn-sm btn-light toggle-panel" data-target="legend-panel">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <div id="legend-panel" class="card-body p-2">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-map-marker-alt text-success me-2"></i>
                                    <small class="text-muted">Itens Achados</small>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-map-marker-alt text-warning me-2"></i>
                                    <small class="text-muted">Itens Perdidos</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-map-marker-alt text-info me-2"></i>
                                    <small class="text-muted">Estabelecimentos Parceiros</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Painel de Controles Mobile -->
                <div class="d-block d-md-none position-absolute top-0 end-0 m-2">
                    <button id="filter-toggle" class="btn btn-primary btn-sm rounded-circle shadow" 
                            style="width: 40px; height: 40px;" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#mobile-filter-panel">
                        <i class="fas fa-filter"></i>
                    </button>
                    
                    <div id="mobile-filter-panel" class="collapse position-absolute top-0 end-0 mt-5 z-index-1000">
                        <div class="card shadow-lg" style="width: 280px;">
                            <div class="card-header bg-primary text-white py-2 d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="fas fa-sliders-h me-2"></i>Filtros
                                </h6>
                                <button class="btn btn-sm btn-light" data-bs-toggle="collapse" data-bs-target="#mobile-filter-panel">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="card-body p-3">
                                <!-- Conteúdo dos filtros mobile -->
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Tipo</label>
                                    <div class="btn-group w-100 shadow-sm" role="group">
                                        <input type="radio" class="btn-check" name="tipo_mobile" id="tipo_todos_mobile" value="" checked>
                                        <label class="btn btn-outline-secondary btn-sm" for="tipo_todos_mobile">
                                            <i class="fas fa-layer-group me-1"></i>Todos
                                        </label>
                                        
                                        <input type="radio" class="btn-check" name="tipo_mobile" id="tipo_achados_mobile" value="achado">
                                        <label class="btn btn-outline-success btn-sm" for="tipo_achados_mobile">
                                            <i class="fas fa-hand-holding me-1"></i>Achados
                                        </label>
                                        
                                        <input type="radio" class="btn-check" name="tipo_mobile" id="tipo_perdidos_mobile" value="perdido">
                                        <label class="btn btn-outline-warning btn-sm" for="tipo_perdidos_mobile">
                                            <i class="fas fa-search me-1"></i>Perdidos
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Status</label>
                                    <div class="btn-group w-100 shadow-sm" role="group">
                                        <input type="radio" class="btn-check" name="status_mobile" id="status_todos_mobile" value="" checked>
                                        <label class="btn btn-outline-secondary btn-sm" for="status_todos_mobile">
                                            <i class="fas fa-bars me-1"></i>Todos
                                        </label>
                                        
                                        <input type="radio" class="btn-check" name="status_mobile" id="status_aprovado_mobile" value="aprovado">
                                        <label class="btn btn-outline-success btn-sm" for="status_aprovado_mobile">
                                            <i class="fas fa-check-circle me-1"></i>Aprovados
                                        </label>
                                        
                                        <input type="radio" class="btn-check" name="status_mobile" id="status_estabelecimento_mobile" value="em_estabelecimento">
                                        <label class="btn btn-outline-info btn-sm" for="status_estabelecimento_mobile">
                                            <i class="fas fa-store me-1"></i>Estabelecimentos Parceiros
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Categoria</label>
                                    <div class="input-group input-group-sm shadow-sm">
                                        <span class="input-group-text bg-white"><i class="fas fa-tag text-muted"></i></span>
                                        <select class="form-select" id="categoria_mobile">
                                            <option value="">Todas as categorias</option>
                                            @foreach($categorias as $categoria)
                                                <option value="{{ $categoria->id }}">{{ $categoria->nome_categoria }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Data</label>
                                    <div class="input-group input-group-sm shadow-sm">
                                        <span class="input-group-text bg-white"><i class="fas fa-calendar text-muted"></i></span>
                                        <input type="date" class="form-control" id="data_mobile">
                                    </div>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary btn-sm shadow-sm" onclick="aplicarFiltros(true)">
                                        <i class="fas fa-filter me-1"></i>Aplicar Filtros
                                    </button>
                                    
                                    <button class="btn btn-outline-secondary btn-sm shadow-sm" onclick="resetarFiltros(true)">
                                        <i class="fas fa-undo me-1"></i>Limpar Filtros
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Legenda Mobile -->
                <div class="d-block d-md-none position-absolute top-0 start-0 m-2">
                    <button id="legend-toggle" class="btn btn-light btn-sm rounded-circle shadow" 
                            style="width: 40px; height: 40px;" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#mobile-legend-panel"
                            title="Legenda">
                        <i class="fas fa-info"></i>
                    </button>
                    
                    <div id="mobile-legend-panel" class="collapse position-absolute top-0 start-0 mt-5 z-index-1000">
                        <div class="card shadow-lg" style="width: 180px;">
                            <div class="card-header bg-primary text-white py-2">
                                <h6 class="mb-0 d-flex align-items-center">
                                    <i class="fas fa-info-circle me-2"></i>Legenda
                                </h6>
                            </div>
                            <div class="card-body p-2">
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-map-marker-alt text-success me-2"></i>
                                        <small class="text-muted">Itens Achados</small>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-map-marker-alt text-warning me-2"></i>
                                        <small class="text-muted">Itens Perdidos</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-map-marker-alt text-info me-2"></i>
                                        <small class="text-muted">Estabelecimentos Parceiros</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Botão de Localização -->
                <div class="position-absolute bottom-0 start-50 translate-middle-x mb-3">
                    <button id="locate-btn" class="btn btn-light btn-sm rounded-circle shadow" 
                            style="width: 40px; height: 40px;" 
                            title="Minha localização">
                        <i class="fas fa-location-arrow"></i>
                    </button>
                </div>
                
                <!-- Contador de Itens -->
                <div class="position-absolute top-0 start-50 translate-middle-x mt-3">
                    <div class="badge bg-white text-dark shadow-sm p-2">
                        <i class="fas fa-map-marker-alt text-primary me-1"></i>
                        <span id="item-count">{{ $itens->count() }}</span> itens encontrados
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Responsividade aprimorada para o painel de filtros */
    .card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0,0,0,0.10);
    }
    .card-header {
        border-bottom: none;
        border-radius: 12px 12px 0 0;
    }
    #filter-panel, #legend-panel, #mobile-filter-panel, #mobile-legend-panel {
        max-height: 70vh;
        overflow-y: auto;
    }
    @media (max-width: 767.98px) {
        #map {
            height: 60vh !important;
        }
        .card {
            width: 100% !important;
            min-width: 0;
        }
        .position-absolute.top-0.end-0.m-3.d-none.d-md-block,
        .position-absolute.top-0.start-0.m-3.d-none.d-md-block {
            display: none !important;
        }
        #mobile-filter-panel, #mobile-legend-panel {
            width: 95vw !important;
            left: 2.5vw;
        }
    }
    /* Botões flutuantes maiores e mais acessíveis */
    #locate-btn, #filter-toggle, #legend-toggle {
        width: 48px;
        height: 48px;
        font-size: 1.3rem;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
        transition: box-shadow 0.2s;
    }
    #locate-btn:hover, #filter-toggle:hover, #legend-toggle:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.18);
    }
    /* Marcadores customizados */
    .leaflet-marker-icon {
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        transition: transform 0.2s;
    }
    .leaflet-marker-icon:hover {
        transform: scale(1.12);
        z-index: 999;
    }
    /* Popups mais claros e legíveis */
    .leaflet-popup-content-wrapper {
        border-radius: 10px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.13);
        font-size: 1rem;
    }
    .info-window {
        max-width: 320px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .info-window img {
        width: 100%;
        height: 160px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }
    .info-window h6 {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }
    .info-window p {
        font-size: 0.92rem;
        color: #666;
        margin-bottom: 6px;
    }
    .info-window .badge {
        font-size: 0.8rem;
        font-weight: 500;
    }
    /* Feedback visual ao aplicar filtros */
    .fade-out {
        opacity: 0.5;
        transition: opacity 0.3s;
    }
    .fade-in {
        opacity: 1;
        transition: opacity 0.3s;
    }
</style>
@endpush

@push('scripts')
<script>
    // Definir a chave da API globalmente
    const GOOGLE_MAPS_API_KEY = "{{ $googleMapsApiKey }}";
    
    // Inicialização do mapa
    let map;
    let markers = [];
    let infoWindows = [];
    
    // Função para inicializar o mapa
    async function initMap() {
        // Coordenadas do centro do mapa (Campo Grande - MS)
        const center = { lat: -20.4697105, lng: -54.620121100000006 };

        // Criar o mapa
        const mapElement = document.getElementById("map");
        if (!mapElement) {
            console.error("Elemento do mapa não encontrado");
            return;
        }

        map = new google.maps.Map(mapElement, {
            zoom: 13,
            center: center,
            mapId: "mapa_achados_perdidos", // ID único para o mapa
            mapTypeControl: true,
            streetViewControl: true,
            fullscreenControl: true,
        });

        // Itens do banco de dados
        const itens = @json($itens);
        
        // Criar marcadores para cada item
        itens.forEach(item => {
            if (!item.localizacao || !item.localizacao.latitude || !item.localizacao.longitude) {
                return;
            }
            
            // Definir ícone baseado no tipo do item
            let icon = '';
            if (item.tipo === 'achado') {
                icon = 'https://maps.google.com/mapfiles/ms/icons/green-dot.png';
            } else if (item.tipo === 'perdido') {
                icon = 'https://maps.google.com/mapfiles/ms/icons/yellow-dot.png';
            }
            
            // Criar marcador usando AdvancedMarkerElement
            const marker = new google.maps.marker.AdvancedMarkerElement({
                position: { 
                    lat: parseFloat(item.localizacao.latitude), 
                    lng: parseFloat(item.localizacao.longitude) 
                },
                map: map,
                title: item.descricao,
                gmpDraggable: false,
                gmpClickable: true,
                content: createMarkerContent(icon)
            });
            
            // Conteúdo do infoWindow
            const contentString = `
                <div class="info-window">
                    ${item.fotos && item.fotos.length > 0 ? `<img src="/storage/${item.fotos[0].caminho}" alt="${item.descricao}" class="img-fluid mb-2" style="max-height: 150px; width: 100%; object-fit: cover;">` : ''}
                    <h6 class="mb-2">${item.descricao}</h6>
                    <p class="mb-1"><strong>Tipo:</strong> ${item.tipo === 'achado' ? 'Item Achado' : 'Item Perdido'}</p>
                    <p class="mb-1"><strong>Categoria:</strong> ${item.categoria ? item.categoria.nome_categoria : 'Não informada'}</p>
                    <p class="mb-1"><strong>Local:</strong> ${item.localizacao.endereco}</p>
                    <p class="mb-2"><strong>Data:</strong> ${item.tipo === 'achado' ? new Date(item.data_encontrado).toLocaleDateString('pt-BR') : new Date(item.data_perdido).toLocaleDateString('pt-BR')}</p>
                    <a href="/user/itens/${item.id}" class="btn btn-sm btn-primary w-100">Ver Detalhes</a>
                </div>
            `;
            
            // Criar infoWindow
            const infoWindow = new google.maps.InfoWindow({
                content: contentString,
                maxWidth: 300
            });
            
            // Evento de clique no marcador usando gmp-click
            marker.addListener("gmp-click", () => {
                infoWindow.open(map, marker);
            });
            
            // Adicionar marcador e infoWindow aos arrays
            markers.push(marker);
            infoWindows.push(infoWindow);
        });

        // Adicionar marcadores para estabelecimentos parceiros
        const estabelecimentos = @json($estabelecimentos ?? []);
        estabelecimentos.forEach(estabelecimento => {
            if (!estabelecimento.latitude || !estabelecimento.longitude) {
                return;
            }

            const marker = new google.maps.marker.AdvancedMarkerElement({
                position: {
                    lat: parseFloat(estabelecimento.latitude),
                    lng: parseFloat(estabelecimento.longitude)
                },
                map: map,
                title: estabelecimento.nome,
                gmpDraggable: false,
                gmpClickable: true,
                content: createMarkerContent('https://maps.google.com/mapfiles/ms/icons/blue-dot.png')
            });

            const contentString = `
                <div class="info-window">
                    <h6 class="mb-2">${estabelecimento.nome}</h6>
                    <p class="mb-1"><strong>Endereço:</strong> ${estabelecimento.endereco}</p>
                    <p class="mb-1"><strong>Horário:</strong> ${estabelecimento.horario_funcionamento}</p>
                    <p class="mb-1"><strong>Contato:</strong> ${estabelecimento.telefone}</p>
                </div>
            `;

            const infoWindow = new google.maps.InfoWindow({
                content: contentString,
                maxWidth: 300
            });

            marker.addListener("gmp-click", () => {
                infoWindow.open(map, marker);
            });

            markers.push(marker);
            infoWindows.push(infoWindow);
        });
        
        // Botão de localização
        document.getElementById('locate-btn').addEventListener('click', () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };
                        map.setCenter(pos);
                        map.setZoom(15);
                    },
                    () => {
                        alert("Não foi possível obter sua localização.");
                    }
                );
            } else {
                alert("Seu navegador não suporta geolocalização.");
            }
        });
    }
    
    // Função para criar o conteúdo do marcador
    function createMarkerContent(iconUrl) {
        const div = document.createElement('div');
        div.style.width = '32px';
        div.style.height = '32px';
        div.style.backgroundImage = `url(${iconUrl})`;
        div.style.backgroundSize = 'contain';
        div.style.backgroundRepeat = 'no-repeat';
        return div;
    }
    
    // Feedback visual ao aplicar filtros
    function aplicarFiltros(isMobile = false) {
        const mapDiv = document.getElementById('map');
        mapDiv.classList.add('fade-out');
        
        // Obter valores dos filtros
        const tipo = isMobile ? 
            document.querySelector('input[name="tipo_mobile"]:checked').value : 
            document.querySelector('input[name="tipo"]:checked').value;
            
        const status = isMobile ? 
            document.querySelector('input[name="status_mobile"]:checked').value : 
            document.querySelector('input[name="status"]:checked').value;
            
        const categoria = isMobile ? 
            document.getElementById('categoria_mobile').value : 
            document.getElementById('categoria').value;
            
        const data = isMobile ? 
            document.getElementById('data_mobile').value : 
            document.getElementById('data').value;
        
        // Aplicar filtros aos marcadores
        let visibleCount = 0;
        
        markers.forEach(marker => {
            let visible = true;
            
            // Filtro de tipo
            if (tipo && marker.type !== tipo) {
                visible = false;
            }
            
            // Filtro de status
            if (status && marker.status !== status) {
                visible = false;
            }
            
            // Atualizar visibilidade do marcador
            marker.setVisible(visible);
            
            if (visible) visibleCount++;
        });
        
        // Atualizar contador
        document.getElementById('item-count').textContent = visibleCount;
        
        setTimeout(() => {
            mapDiv.classList.remove('fade-out');
            mapDiv.classList.add('fade-in');
            setTimeout(() => mapDiv.classList.remove('fade-in'), 300);
        }, 250);
    }
    
    // Resetar filtros
    function resetarFiltros(isMobile = false) {
        // Resetar inputs
        if (isMobile) {
            document.getElementById('tipo_todos_mobile').checked = true;
            document.getElementById('status_todos_mobile').checked = true;
            document.getElementById('categoria_mobile').value = '';
            document.getElementById('data_mobile').value = '';
        } else {
            document.getElementById('tipo_todos').checked = true;
            document.getElementById('status_todos').checked = true;
            document.getElementById('categoria').value = '';
            document.getElementById('data').value = '';
        }
        
        // Mostrar todos os marcadores
        markers.forEach(marker => marker.setVisible(true));
        
        // Atualizar contador
        document.getElementById('item-count').textContent = markers.length;
    }
    
    // Ajuste para painel de filtros responsivo
    document.querySelectorAll('.toggle-panel').forEach(btn => {
        btn.addEventListener('click', function() {
            const target = document.getElementById(this.dataset.target);
            if(target) target.classList.toggle('d-none');
        });
    });
    
    // Scroll suave para filtros em mobile
    if(window.innerWidth < 768) {
        document.getElementById('filter-toggle')?.addEventListener('click', function() {
            setTimeout(() => {
                document.getElementById('mobile-filter-panel')?.scrollIntoView({behavior:'smooth'});
            }, 300);
        });
    }
</script>

<!-- Carrega a API do Google Maps com async e defer -->
<script>
    // Função para carregar a API do Google Maps
    function loadGoogleMaps() {
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=${GOOGLE_MAPS_API_KEY}&callback=initMap&v=beta&libraries=marker`;
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    }

    // Carrega a API quando o documento estiver pronto
    document.addEventListener('DOMContentLoaded', loadGoogleMaps);
</script>
@endpush
@endsection