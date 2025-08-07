@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">Detalhes do Item</h2>
                <span class="badge bg-{{ $item->tipo === 'achado' ? 'success' : 'warning' }} fs-6">
                    {{ $item->tipo === 'achado' ? 'Achado' : 'Perdido' }}
                </span>
            </div>
            
            <div class="row">
                <!-- Galeria de Fotos -->
                <div class="col-md-6 mb-4">
                    @if($item->fotos->count() > 0)
                        <div id="itemCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner rounded shadow-sm">
                                @foreach($item->fotos as $index => $foto)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/'.$foto->caminho) }}" 
                                             class="d-block w-100" 
                                             alt="Foto do item"
                                             style="height: 350px; object-fit: contain; background-color: #f8f9fa;">
                                    </div>
                                @endforeach
                            </div>
                            @if($item->fotos->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#itemCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#itemCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Próximo</span>
                                </button>
                            @endif
                        </div>
                        <div class="d-flex mt-2 overflow-auto">
                            @foreach($item->fotos as $index => $foto)
                                <div class="me-2" style="min-width: 80px;">
                                    <img src="{{ asset('storage/'.$foto->caminho) }}" 
                                         class="img-thumbnail" 
                                         alt="Miniatura"
                                         style="height: 80px; width: 80px; object-fit: cover; cursor: pointer;"
                                         onclick="$('#itemCarousel').carousel({{ $index }})">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                             style="height: 350px;">
                            <i class="fas fa-image fa-4x text-muted"></i>
                        </div>
                    @endif
                </div>
                
                <!-- Informações do Item -->
                <div class="col-md-6">
                    <div class="card h-100 border-0">
                        <div class="card-body">
                            <h3 class="card-title mb-3">{{ $item->descricao }}</h3>
                            
                            <div class="mb-3">
                                <h5 class="text-muted mb-2">Categoria</h5>
                                <p class="card-text">
                                    <i class="fas fa-tag me-2 text-primary"></i>
                                    {{ $item->categoria->nome_categoria }}
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <h5 class="text-muted mb-2">Data</h5>
                                <p class="card-text">
                                    <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                    @if($item->tipo === 'achado')
                                        Encontrado em: {{ \Carbon\Carbon::parse($item->data_encontrado)->format('d/m/Y') }}
                                    @else
                                        Perdido em: {{ \Carbon\Carbon::parse($item->data_perdido)->format('d/m/Y') }}
                                    @endif
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <h5 class="text-muted mb-2">Status</h5>
                                <p class="card-text">
                                    <span class="badge bg-{{ $item->status === 'aprovado' ? 'success' : ($item->status === 'pendente' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <h5 class="text-muted mb-2">Registrado em</h5>
                                <p class="card-text">
                                    <i class="fas fa-clock me-2 text-primary"></i>
                                    {{ $item->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Descrição Completa -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h4 class="mb-0">Descrição Completa</h4>
        </div>
        <div class="card-body">
            <p>{{ $item->descricao }}</p>
        </div>
    </div>
    
    <!-- Localização -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Localização</h4>
            <a href="https://www.google.com/maps?q={{ $item->localizacao->latitude }},{{ $item->localizacao->longitude }}" 
               target="_blank" 
               class="btn btn-outline-primary btn-sm">
                <i class="fas fa-map-marked-alt me-1"></i>
                Abrir no Google Maps
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Local:</strong> {{ $item->localizacao->nome_local }}</p>
                    <p><strong>Endereço:</strong> {{ $item->localizacao->endereco }}</p>
                    <p><strong>Referência:</strong> {{ $item->localizacao->referencia }}</p>
                </div>
                <div class="col-md-6">
                    <div id="map" style="height: 300px;" class="rounded"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Informações do Parceiro (se estiver em estabelecimento) -->
    @if($item->status === 'em_estabelecimento' && $item->parceiro)
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-store me-2 text-primary"></i>Item em Estabelecimento Parceiro</h4>
            <span class="badge bg-info">Em Estabelecimento</span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h5>{{ $item->parceiro->nome_estabelecimento }}</h5>
                    <p class="mb-2"><i class="fas fa-map-marker-alt me-2 text-primary"></i>{{ $item->parceiro->localizacao->endereco }}</p>
                    <p class="mb-2"><i class="fas fa-phone me-2 text-primary"></i>{{ $item->parceiro->telefone_comercial }}</p>
                    <p class="mb-2"><i class="fas fa-clock me-2 text-primary"></i>{{ $item->parceiro->horario_funcionamento }}</p>
                    
                    <div class="mt-3">
                        <a href="/chatify/{{ $item->parceiro->user_id }}" 
                           class="btn btn-primary">
                            <i class="fas fa-comments me-2"></i>Conversar com o Parceiro
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 150px;">
                        @if($item->parceiro->logo)
                            <img src="{{ asset('storage/'.$item->parceiro->logo) }}" alt="Logo do parceiro" class="img-fluid" style="max-height: 140px;">
                        @else
                            <i class="fas fa-store fa-4x text-primary opacity-50"></i>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Informações do Usuário -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h4 class="mb-0">Cadastrado por</h4>
        </div>
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    @if($item->usuario->foto)
                        <img src="{{ asset('storage/'.$item->usuario->foto) }}" 
                             alt="Foto do usuário" 
                             class="rounded-circle" 
                             style="width: 80px; height: 80px; object-fit: cover;">
                    @else
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-user fa-2x text-muted"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <h5 class="mb-1">{{ $item->usuario->name }}</h5>
                    <p class="text-muted mb-2">
                        <i class="fas fa-envelope me-1"></i>
                        {{ $item->usuario->email }}
                    </p>
                    <a href="/chatify/{{ $item->usuario->id }}" class="btn btn-success btn-sm">
                        <i class="fas fa-comments me-1"></i>
                        Iniciar Conversa
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Google Maps API Script -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" defer></script>
<script>
    // Função para inicializar o mapa do Google
    function initMap() {
        // Coordenadas do item
        const itemLocation = { lat: {{ $item->localizacao->latitude }}, lng: {{ $item->localizacao->longitude }} };
        
        // Opções do mapa
        const mapOptions = {
            zoom: 15,
            center: itemLocation,
            mapTypeControl: true,
            streetViewControl: true,
            fullscreenControl: true
        };
        
        // Criar o mapa
        const map = new google.maps.Map(document.getElementById("map"), mapOptions);
        
        // Adicionar marcador
        const marker = new google.maps.Marker({
            position: itemLocation,
            map: map,
            title: "{{ $item->localizacao->nome_local }}",
            animation: google.maps.Animation.DROP
        });
        
        // Adicionar janela de informação
        const infoWindow = new google.maps.InfoWindow({
            content: `<div><strong>{{ $item->localizacao->nome_local }}</strong><br>{{ $item->localizacao->endereco }}</div>`
        });
        
        // Abrir a janela de informação ao clicar no marcador
        marker.addListener("click", () => {
            infoWindow.open(map, marker);
        });
        
        // Abrir a janela de informação por padrão
        infoWindow.open(map, marker);
    }
</script>
@endpush
@endsection