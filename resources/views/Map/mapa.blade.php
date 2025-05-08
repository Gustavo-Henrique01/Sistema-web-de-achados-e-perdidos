@extends('layouts.parceiro')

@section('title', 'Mapa de Itens')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Mapa de Itens</h1>
            <p class="text-muted mb-0">Visualize a localização de itens e estabelecimentos parceiros</p>
        </div>
        <div class="d-flex align-items-center">
            <span class="badge bg-primary rounded-pill fs-6 d-flex align-items-center">
                <i class="fas fa-map-marker-alt me-2"></i>
                <span id="item-count">0</span> itens visíveis
            </span>
        </div>
    </div>
    
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="position-relative">
                <!-- Mapa -->
                <div id="map" style="height: 80vh; width: 100%;"></div>
                
                <!-- Botão de localização -->
                <button id="locate-btn" class="btn btn-light rounded-circle shadow position-absolute bottom-0 end-0 m-3" style="width: 45px; height: 45px;">
                    <i class="fas fa-location-arrow text-primary"></i>
                </button>
                
                <!-- Painel de Controles -->
                <div class="position-absolute top-0 end-0 m-3 d-none d-md-block">
                    <div class="card shadow-lg border-0" style="width: 300px; border-radius: var(--border-radius);">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 d-flex align-items-center fw-bold">
                                <i class="fas fa-sliders-h me-2 text-primary"></i>Filtros
                            </h6>
                            <button class="btn btn-sm btn-outline-secondary rounded-circle toggle-panel" data-target="filter-panel" style="width: 30px; height: 30px;">
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
                                <button class="btn btn-primary btn-sm shadow-sm d-flex align-items-center justify-content-center" onclick="aplicarFiltros()">
                                    <i class="fas fa-filter me-2"></i>Aplicar Filtros
                                </button>
                                
                                <button class="btn btn-outline-secondary btn-sm shadow-sm d-flex align-items-center justify-content-center" onclick="resetarFiltros()">
                                    <i class="fas fa-undo me-2"></i>Limpar Filtros
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Legenda -->
                <div class="position-absolute top-0 start-0 m-3 d-none d-md-block">
                    <div class="card shadow-lg border-0" style="width: 200px; border-radius: var(--border-radius);">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 d-flex align-items-center fw-bold">
                                <i class="fas fa-info-circle me-2 text-primary"></i>Legenda
                            </h6>
                            <button class="btn btn-sm btn-outline-secondary rounded-circle toggle-panel" data-target="legend-panel" style="width: 30px; height: 30px;">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <div id="legend-panel" class="card-body p-3">
                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex align-items-center p-1 border-start border-success border-3 ps-2 rounded-start">
                                    <div class="d-flex align-items-center justify-content-center bg-success bg-opacity-10 p-1 rounded-circle me-2" style="width: 28px; height: 28px;">
                                        <i class="fas fa-map-marker-alt text-success"></i>
                                    </div>
                                    <span class="small">Itens Achados</span>
                                </div>
                                <div class="d-flex align-items-center p-1 border-start border-warning border-3 ps-2 rounded-start">
                                    <div class="d-flex align-items-center justify-content-center bg-warning bg-opacity-10 p-1 rounded-circle me-2" style="width: 28px; height: 28px;">
                                        <i class="fas fa-map-marker-alt text-warning"></i>
                                    </div>
                                    <span class="small">Itens Perdidos</span>
                                </div>
                                <div class="d-flex align-items-center p-1 border-start border-info border-3 ps-2 rounded-start">
                                    <div class="d-flex align-items-center justify-content-center bg-info bg-opacity-10 p-1 rounded-circle me-2" style="width: 28px; height: 28px;">
                                        <i class="fas fa-map-marker-alt text-info"></i>
                                    </div>
                                    <span class="small">Estabelecimentos Parceiros</span>
                                </div>
                                <div class="d-flex align-items-center p-1 border-start border-purple border-3 ps-2 rounded-start">
                                    <div class="d-flex align-items-center justify-content-center bg-purple bg-opacity-10 p-1 rounded-circle me-2" style="width: 28px; height: 28px;">
                                        <i class="fas fa-map-marker-alt" style="color: purple !important;"></i>
                                    </div>
                                    <span class="small">Itens em Estabelecimentos</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Painel de Controles Mobile -->
                <div class="d-block d-md-none position-absolute top-0 end-0 m-3">
                    <button id="filter-toggle" class="btn btn-primary btn-sm rounded-circle shadow-sm" 
                            style="width: 45px; height: 45px;" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#mobile-filter-panel">
                        <i class="fas fa-filter"></i>
                    </button>
                    
                    <div id="mobile-filter-panel" class="collapse position-absolute top-0 end-0 mt-5 z-index-1000">
                        <div class="card shadow-lg border-0" style="width: 300px; border-radius: var(--border-radius);">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-sliders-h me-2 text-primary"></i>Filtros
                                </h6>
                                <button class="btn btn-sm btn-outline-secondary rounded-circle" data-bs-toggle="collapse" data-bs-target="#mobile-filter-panel" style="width: 30px; height: 30px;">
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
                                    <button class="btn btn-primary btn-sm shadow-sm d-flex align-items-center justify-content-center" onclick="aplicarFiltros(true)">
                                        <i class="fas fa-filter me-2"></i>Aplicar Filtros
                                    </button>
                                    
                                    <button class="btn btn-outline-secondary btn-sm shadow-sm d-flex align-items-center justify-content-center" onclick="resetarFiltros(true)">
                                        <i class="fas fa-undo me-2"></i>Limpar Filtros
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Legenda Mobile -->
                <div class="d-block d-md-none position-absolute top-0 start-0 m-3">
                    <button id="legend-toggle" class="btn btn-light btn-sm rounded-circle shadow-sm" 
                            style="width: 45px; height: 45px;" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#mobile-legend-panel"
                            title="Legenda">
                        <i class="fas fa-info-circle text-primary"></i>
                    </button>
                    
                    <div id="mobile-legend-panel" class="collapse position-absolute top-0 start-0 mt-5 z-index-1000">
                        <div class="card shadow-lg border-0" style="width: 200px; border-radius: var(--border-radius);">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 d-flex align-items-center fw-bold">
                                    <i class="fas fa-info-circle me-2 text-primary"></i>Legenda
                                </h6>
                                <button class="btn btn-sm btn-outline-secondary rounded-circle" data-bs-toggle="collapse" data-bs-target="#mobile-legend-panel" style="width: 30px; height: 30px;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex flex-column gap-2">
                                    <div class="d-flex align-items-center p-1 border-start border-success border-3 ps-2 rounded-start">
                                        <div class="d-flex align-items-center justify-content-center bg-success bg-opacity-10 p-1 rounded-circle me-2" style="width: 28px; height: 28px;">
                                            <i class="fas fa-map-marker-alt text-success"></i>
                                        </div>
                                        <span class="small">Itens Achados</span>
                                    </div>
                                    <div class="d-flex align-items-center p-1 border-start border-warning border-3 ps-2 rounded-start">
                                        <div class="d-flex align-items-center justify-content-center bg-warning bg-opacity-10 p-1 rounded-circle me-2" style="width: 28px; height: 28px;">
                                            <i class="fas fa-map-marker-alt text-warning"></i>
                                        </div>
                                        <span class="small">Itens Perdidos</span>
                                    </div>
                                    <div class="d-flex align-items-center p-1 border-start border-info border-3 ps-2 rounded-start">
                                        <div class="d-flex align-items-center justify-content-center bg-info bg-opacity-10 p-1 rounded-circle me-2" style="width: 28px; height: 28px;">
                                            <i class="fas fa-map-marker-alt text-info"></i>
                                        </div>
                                        <span class="small">Estabelecimentos Parceiros</span>
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
    
    <div class="d-flex justify-content-center mt-4 mb-2">
        <a href="{{ route('paginaInicial') }}" class="btn btn-outline-secondary d-flex align-items-center">
            <i class="fas fa-arrow-left me-2"></i>Voltar para a Página Inicial
        </a>
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
            mapId: "mapa_achados_perdidos",
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
            
            // Definir ícone baseado no tipo do item e status
            let icon = '';
            if (item.status === 'em_estabelecimento') {
                icon = 'https://maps.google.com/mapfiles/ms/icons/purple-dot.png'; // Cor roxa para itens em estabelecimento
            } else if (item.tipo === 'achado') {
                icon = 'https://maps.google.com/mapfiles/ms/icons/green-dot.png';
            } else if (item.tipo === 'perdido') {
                icon = 'https://maps.google.com/mapfiles/ms/icons/yellow-dot.png';
            }
            
            // Criar marcador usando Marker padrão para garantir que as cores sejam exibidas corretamente
            const marker = new google.maps.Marker({
                position: { 
                    lat: parseFloat(item.localizacao.latitude), 
                    lng: parseFloat(item.localizacao.longitude) 
                },
                map: map,
                title: item.descricao,
                draggable: false,
                icon: icon
            });

            // Adicionar atributos personalizados ao marcador para os filtros
            marker.itemData = {
                tipo: item.tipo,
                status: item.status,
                categoria: item.id_categoria,
                data: item.tipo === 'achado' ? item.data_encontrado : item.data_perdido
            };
            
            // Conteúdo do infoWindow
            let estabelecimentoInfo = '';
            
            // Adicionar informações do estabelecimento se o item estiver em um parceiro
            if (item.status === 'em_estabelecimento' && item.parceiro) {
                const nomeEstabelecimento = item.parceiro.nome_estabelecimento || 'Não informado';
                const endereco = item.parceiro.localizacao && item.parceiro.localizacao.endereco ? item.parceiro.localizacao.endereco : 'Não informado';
                const telefone = item.parceiro.telefone_comercial || 'Não informado';
                
                const horario = item.parceiro.horario_funcionamento || 'Não informado';
                const logoHtml = item.parceiro.logo ? `<div class="text-center mb-2"><img src="/storage/${item.parceiro.logo}" alt="Logo ${nomeEstabelecimento}" class="img-fluid" style="max-height: 60px; max-width: 100%;"></div>` : '';
                
                estabelecimentoInfo = `
                    <div class="mt-2 mb-2 p-2 bg-light rounded">
                        <h6 class="mb-2 border-bottom pb-2"><i class="fas fa-store me-1 text-primary"></i> Item em Estabelecimento Parceiro</h6>
                        ${logoHtml}
                        <p class="mb-1"><strong>Estabelecimento:</strong> ${nomeEstabelecimento}</p>
                        <p class="mb-1"><i class="fas fa-map-marker-alt me-1 text-primary"></i> <strong>Endereço:</strong> ${endereco}</p>
                        <p class="mb-1"><i class="fas fa-phone me-1 text-primary"></i> <strong>Telefone:</strong> ${telefone}</p>
                        <p class="mb-0"><i class="fas fa-clock me-1 text-primary"></i> <strong>Horário:</strong> ${horario}</p>
                        <div class="mt-2">
                            <a href="/mapa/parceiro/${item.parceiro.id}" class="btn btn-sm btn-outline-primary w-100"><i class="fas fa-info-circle me-1"></i> Ver Parceiro</a>
                        </div>
                    </div>
                `;
            }
            
            const contentString = `
                <div class="info-window">
                    ${item.fotos && item.fotos.length > 0 ? `<img src="/storage/${item.fotos[0].caminho}" alt="${item.descricao}" class="img-fluid mb-2" style="max-height: 150px; width: 100%; object-fit: cover;">` : ''}
                    <h6 class="mb-2">${item.descricao}</h6>
                    <p class="mb-1"><strong>Tipo:</strong> ${item.tipo === 'achado' ? 'Item Achado' : 'Item Perdido'}</p>
                    <p class="mb-1"><strong>Categoria:</strong> ${item.categoria ? item.categoria.nome_categoria : 'Não informada'}</p>
                    <p class="mb-1"><strong>Local:</strong> ${item.localizacao.endereco}</p>
                    <p class="mb-2"><strong>Data:</strong> ${item.tipo === 'achado' ? new Date(item.data_encontrado).toLocaleDateString('pt-BR') : new Date(item.data_perdido).toLocaleDateString('pt-BR')}</p>
                    ${estabelecimentoInfo}
                    <a href="/mapa/item/${item.id}" class="btn btn-sm btn-primary w-100">Ver Detalhes</a>
                </div>
            `;
            
            // Criar infoWindow
            const infoWindow = new google.maps.InfoWindow({
                content: contentString,
                maxWidth: 300
            });
            
            // Evento de clique no marcador
            marker.addListener("click", () => {
                // Fechar todas as outras janelas de info
                infoWindows.forEach(iw => iw.close());
                infoWindow.open(map, marker);
            });
            
            // Adicionar marcador e infoWindow aos arrays
            markers.push(marker);
            infoWindows.push(infoWindow);
        });
        
        // Atualizar contador de itens visíveis
        document.getElementById('item-count').textContent = markers.length;
        
        // Adicionar funcionalidade ao botão de localização
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
        
        // Adicionar marcadores para estabelecimentos parceiros
        const parceiros = @json($parceiros);
        parceiros.forEach(parceiro => {
            if (!parceiro.localizacao || !parceiro.localizacao.latitude || !parceiro.localizacao.longitude) {
                return;
            }

            const marker = new google.maps.Marker({
                position: {
                    lat: parseFloat(parceiro.localizacao.latitude),
                    lng: parseFloat(parceiro.localizacao.longitude)
                },
                map: map,
                title: parceiro.nome_estabelecimento || 'Parceiro',
                draggable: false,
                icon: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png'
            });

            // Adicionar atributos para filtro
            marker.itemData = {
                tipo: 'parceiro',
                status: 'em_estabelecimento',
                categoria: null,
                data: null
            };

            const contentString = `
                <div class="info-window">
                    ${parceiro.logo ? `<div class="text-center mb-2"><img src="/storage/${parceiro.logo}" alt="Logo ${parceiro.nome_estabelecimento}" class="img-fluid" style="max-height: 80px; max-width: 100%;"></div>` : ''}
                    <h6 class="mb-2">${parceiro.nome_estabelecimento}</h6>
                    <p class="mb-1"><i class="fas fa-map-marker-alt me-1 text-primary"></i> <strong>Endereço:</strong> ${parceiro.localizacao ? parceiro.localizacao.endereco : 'Não informado'}</p>
                    <p class="mb-1"><i class="fas fa-phone me-1 text-primary"></i> <strong>Telefone:</strong> ${parceiro.telefone_comercial || 'Não informado'}</p>
                    <p class="mb-2"><i class="fas fa-clock me-1 text-primary"></i> <strong>Horário:</strong> ${parceiro.horario_funcionamento || 'Não informado'}</p>
                    <a href="/mapa/parceiro/${parceiro.id}" class="btn btn-sm btn-primary w-100"><i class="fas fa-info-circle me-1"></i> Ver Detalhes</a>
                </div>
            `;

            const infoWindow = new google.maps.InfoWindow({
                content: contentString,
                maxWidth: 300
            });

            marker.addListener("click", () => {
                infoWindows.forEach(iw => iw.close());
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
    
    // Função para criar o conteúdo do marcador (não mais utilizada, mantida para compatibilidade)
    function createMarkerContent(iconUrl) {
        const div = document.createElement('div');
        div.style.width = '32px';
        div.style.height = '32px';
        div.style.backgroundImage = `url(${iconUrl})`;
        div.style.backgroundSize = 'contain';
        div.style.backgroundRepeat = 'no-repeat';
        return div;
    }
    
    // Função para aplicar os filtros
    function aplicarFiltros(isMobile = false) {
        console.log('Aplicando filtros no mapa do parceiro...');
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
        
        console.log('Filtros selecionados:', { tipo, status, categoria, data });
        
        // Fechar todas as janelas de info
        infoWindows.forEach(iw => iw.close());
        
        // Aplicar filtros aos marcadores
        let visibleCount = 0;
        
        markers.forEach(marker => {
            let visible = true;
            const itemData = marker.itemData;
            
            console.log('Verificando marcador:', itemData);
            
            // Filtro de tipo
            if (tipo && tipo !== '') {
                if (tipo === 'achado' && itemData.tipo !== 'achado') visible = false;
                if (tipo === 'perdido' && itemData.tipo !== 'perdido') visible = false;
            }
            
            // Filtro de status
            if (status && status !== '') {
                if (status === 'aprovado' && itemData.status !== 'aprovado') visible = false;
                if (status === 'em_estabelecimento') {
                    // Mostrar apenas estabelecimentos parceiros e itens em estabelecimento
                    if (!(itemData.tipo === 'parceiro' || itemData.status === 'em_estabelecimento')) {
                        visible = false;
                    }
                }
            }
            
            // Filtro de categoria
            if (categoria && categoria !== '' && itemData.categoria != categoria) {
                visible = false;
            }
            
            // Filtro de data
            if (data && data !== '' && itemData.data) {
                const itemDate = new Date(itemData.data).toISOString().split('T')[0];
                if (itemDate !== data) {
                    visible = false;
                }
            }
            
            console.log('Visibilidade do marcador:', visible);
            
            // Atualizar visibilidade do marcador
            marker.setMap(visible ? map : null);
            
            if (visible) visibleCount++;
        });
        
        console.log('Total de marcadores visíveis:', visibleCount);
        
        // Atualizar contador
        const itemCountElement = document.getElementById('item-count');
        if (itemCountElement) {
            itemCountElement.textContent = visibleCount;
        }
        
        setTimeout(() => {
            mapDiv.classList.remove('fade-out');
            mapDiv.classList.add('fade-in');
            setTimeout(() => mapDiv.classList.remove('fade-in'), 300);
        }, 250);
    }
    
    // Resetar filtros
    function resetarFiltros(isMobile = false) {
        console.log('Resetando filtros no mapa do parceiro...');
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
        markers.forEach(marker => {
            marker.setMap(map);
        });
        
        // Atualizar contador
        const itemCountElement = document.getElementById('item-count');
        if (itemCountElement) {
            itemCountElement.textContent = markers.length;
        }
        
        // Fechar todas as janelas de info
        infoWindows.forEach(iw => iw.close());
    }
    
    // Ajuste para painel de filtros responsivo
    document.querySelectorAll('.toggle-panel').forEach(btn => {
        btn.addEventListener('click', function() {
            const target = document.getElementById(this.dataset.target);
            if(target) {
                const isVisible = !target.classList.contains('d-none');
                target.classList.toggle('d-none');
                
                // Atualizar o ícone do botão
                const icon = this.querySelector('i');
                if (icon) {
                    icon.className = isVisible ? 'fas fa-chevron-down' : 'fas fa-chevron-up';
                }
            }
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
        script.src = `https://maps.googleapis.com/maps/api/js?key=${GOOGLE_MAPS_API_KEY}&callback=initMap`;
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    }

    // Carrega a API quando o documento estiver pronto
    document.addEventListener('DOMContentLoaded', loadGoogleMaps);
</script>
@endpush
@endsection