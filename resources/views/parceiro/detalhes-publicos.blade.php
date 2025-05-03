@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <!-- Informações do Parceiro -->
                <div class="col-md-8">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="mb-0">{{ $parceiro->nome_estabelecimento }}</h2>
                        <span class="badge bg-primary fs-6">
                            Parceiro
                        </span>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-muted">{{ $parceiro->descricao }}</p>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h5 class="text-muted mb-2">Horário de Funcionamento</h5>
                                <p class="card-text">
                                    <i class="fas fa-clock me-2 text-primary"></i>
                                    {{ $parceiro->horario_funcionamento ?? 'Não informado' }}
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <h5 class="text-muted mb-2">Contato</h5>
                                <p class="card-text">
                                    <i class="fas fa-phone me-2 text-primary"></i>
                                    {{ $parceiro->telefone_comercial ?? 'Não informado' }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h5 class="text-muted mb-2">Tipo de Parceiro</h5>
                                <p class="card-text">
                                    <i class="fas fa-store me-2 text-primary"></i>
                                    @if($parceiro->tipo_parceiro == 'ponto_coleta')
                                        Ponto de Coleta
                                    @elseif($parceiro->tipo_parceiro == 'evento')
                                        Local de Evento
                                    @else
                                        Ponto de Coleta e Local de Evento
                                    @endif
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <h5 class="text-muted mb-2">Estatísticas</h5>
                                <p class="card-text">
                                    <i class="fas fa-box me-2 text-primary"></i>
                                    <strong>{{ $estatisticas['total_itens'] }}</strong> itens atualmente
                                </p>
                                <p class="card-text">
                                    <i class="fas fa-check-circle me-2 text-success"></i>
                                    <strong>{{ $estatisticas['itens_devolvidos'] }}</strong> itens devolvidos
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Logo e Mapa -->
                <div class="col-md-4">
                    <div class="mb-4 text-center">
                        @if($parceiro->logo)
                            <img src="{{ asset('storage/' . $parceiro->logo) }}" alt="Logo {{ $parceiro->nome_estabelecimento }}" class="img-fluid rounded" style="max-height: 150px;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 150px;">
                                <i class="fas fa-store fa-4x text-primary opacity-50"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            <div id="parceiro-map" style="height: 200px;" class="rounded"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Localização -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Localização</h4>
            @if($parceiro->localizacao)
            <a href="https://www.google.com/maps?q={{ $parceiro->localizacao->latitude }},{{ $parceiro->localizacao->longitude }}" 
               target="_blank" 
               class="btn btn-outline-primary btn-sm">
                <i class="fas fa-map-marked-alt me-1"></i>
                Abrir no Google Maps
            </a>
            @endif
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    @if($parceiro->localizacao)
                    <p><strong>Local:</strong> {{ $parceiro->localizacao->nome_local ?? 'Não informado' }}</p>
                    <p><strong>Endereço:</strong> {{ $parceiro->localizacao->endereco ?? 'Não informado' }}</p>
                    <p><strong>Referência:</strong> {{ $parceiro->localizacao->referencia ?? 'Não informado' }}</p>
                    @else
                    <p>Localização não disponível</p>
                    @endif
                </div>
                <div class="col-md-6">
                    <div id="location-map" style="height: 300px;" class="rounded"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Itens no Estabelecimento -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h4 class="mb-0">Itens Disponíveis no Estabelecimento</h4>
        </div>
        <div class="card-body">
            @if($parceiro->itens->count() > 0)
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    @foreach($parceiro->itens as $item)
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="position-relative">
                                    @if($item->fotos->count() > 0)
                                        <img src="{{ asset('storage/' . $item->fotos->first()->caminho) }}" 
                                             class="card-img-top" 
                                             alt="Foto do item"
                                             style="height: 180px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 180px;">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                    <span class="position-absolute top-0 start-0 badge {{ $item->tipo === 'achado' ? 'bg-success' : 'bg-warning' }} m-2">
                                        {{ $item->tipo === 'achado' ? 'Achado' : 'Perdido' }}
                                    </span>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ Str::limit($item->descricao, 50) }}</h5>
                                    <p class="card-text text-muted small">
                                        <i class="fas fa-tag me-1"></i> {{ $item->categoria->nome_categoria ?? 'Sem categoria' }}
                                    </p>
                                    <a href="{{ route('itens.show', $item->id) }}" class="btn btn-primary btn-sm w-100">
                                        Ver Detalhes
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Este estabelecimento não possui itens disponíveis no momento.
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<!-- Google Maps API Script -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" defer></script>
<script>
    function initMap() {
        @if($parceiro->localizacao)
            const parceiroLocation = { 
                lat: {{ $parceiro->localizacao->latitude }}, 
                lng: {{ $parceiro->localizacao->longitude }} 
            };
            
            // Mapa pequeno no topo
            const parceiroMap = new google.maps.Map(document.getElementById("parceiro-map"), {
                zoom: 14,
                center: parceiroLocation,
                mapId: "parceiro_map",
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: false,
                zoomControl: false,
            });
            
            // Marcador no mapa pequeno
            new google.maps.Marker({
                position: parceiroLocation,
                map: parceiroMap,
                icon: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png"
            });
            
            // Mapa maior na seção de localização
            const locationMap = new google.maps.Map(document.getElementById("location-map"), {
                zoom: 15,
                center: parceiroLocation,
                mapId: "location_map",
            });
            
            // Marcador no mapa de localização
            const marker = new google.maps.Marker({
                position: parceiroLocation,
                map: locationMap,
                title: "{{ $parceiro->nome_estabelecimento }}",
                icon: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png"
            });
            
            // Info window para o marcador
            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="max-width: 200px;">
                        <h6 class="mb-1">{{ $parceiro->nome_estabelecimento }}</h6>
                        <p class="mb-0 small">{{ $parceiro->localizacao->endereco }}</p>
                    </div>
                `
            });
            
            marker.addListener("click", () => {
                infoWindow.open(locationMap, marker);
            });
        @endif
    }
</script>
@endpush
@endsection
