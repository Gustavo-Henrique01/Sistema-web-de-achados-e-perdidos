@extends('layouts.parceiro')

@section('title', 'Detalhes do Parceiro')

@section('styles')
<style>
    .hover-shadow:hover {
        transform: translateY(-3px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .transition-all {
        transition: all 0.3s ease;
    }
    .card-img-top {
        background-color: #f8f9fa;
    }
    .info-card {
        border-left: 3px solid #0d6efd;
        padding-left: 1rem;
    }
    .compact-card {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .responsive-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1rem;
    }
    @media (max-width: 768px) {
        .responsive-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-3 px-md-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Detalhes do Parceiro</h1>
        <a href="{{ route('mapa.mostrar') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Voltar para o Mapa
        </a>
    </div>

    <!-- Card Principal -->
    <div class="card border-0 shadow-sm mb-4 compact-card">
        <div class="card-body p-3">
            <div class="row g-3">
                <!-- Seção de Logo -->
                <div class="col-md-3 col-lg-2">
                    <div class="d-flex flex-column h-100">
                        @if($parceiro->logo)
                            <img src="{{ asset('storage/' . $parceiro->logo) }}" 
                                alt="{{ $parceiro->nome_estabelecimento }}" 
                                class="img-fluid rounded w-100 mb-2"
                                style="height: 160px; object-fit: contain; background-color: #f8f9fa;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center mb-2"
                                style="height: 160px;">
                                <i class="fas fa-store fa-2x text-muted"></i>
                            </div>
                        @endif
                        
                        <div class="mt-auto">
                            <span class="badge bg-info w-100 mb-2">
                                Parceiro
                            </span>
                            <span class="badge bg-{{ $parceiro->status == 'aprovado' ? 'success' : 'warning' }} w-100">
                                {{ ucfirst($parceiro->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Seção de Informações -->
                <div class="col-md-5 col-lg-6">
                    <h3 class="h5 mb-3">{{ $parceiro->nome_estabelecimento }}</h3>
                    
                    <div class="info-card mb-3">
                        <small class="text-muted">Descrição</small>
                        <p class="mb-0">{{ $parceiro->descricao ?? 'Sem descrição disponível' }}</p>
                    </div>
                    
                    <div class="row g-2">
                        <div class="col-sm-6">
                            <div class="info-card">
                                <small class="text-muted">Horário</small>
                                <p class="mb-0">{{ $parceiro->horario_funcionamento ?? 'Não informado' }}</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="info-card">
                                <small class="text-muted">Contato</small>
                                <p class="mb-0">{{ $parceiro->telefone_comercial ?? 'Não informado' }}</p>
                                <p class="mb-0 small">{{ $parceiro->usuario->email }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($parceiro->localizacao)
                    <div class="info-card mt-2">
                        <small class="text-muted">Endereço</small>
                        <p class="mb-0">{{ $parceiro->localizacao->endereco }}</p>
                        @if($parceiro->localizacao->referencia)
                            <p class="mb-0 small text-muted">{{ $parceiro->localizacao->referencia }}</p>
                        @endif
                    </div>
                    @endif
                </div>
                
                <!-- Seção do Mapa -->
                @if($parceiro->localizacao && $parceiro->localizacao->latitude)
                <div class="col-md-4 col-lg-4">
                    <div class="h-100 d-flex flex-column">
                        <div id="parceiro-map" class="flex-grow-1" style="min-height: 200px; border-radius: 8px; overflow: hidden;"></div>
                        <div class="text-center mt-2">
                            <a href="https://www.google.com/maps?q={{ $parceiro->localizacao->latitude }},{{ $parceiro->localizacao->longitude }}" 
                               target="_blank" 
                               class="btn btn-sm btn-outline-primary mt-2">
                                <i class="fas fa-map-marked-alt"></i> Abrir no Google Maps
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Itens no Estabelecimento -->
   <!-- Itens no Estabelecimento -->
@if($itensEmEstabelecimento && $itensEmEstabelecimento->count() > 0)
<div class="card border-0 shadow-sm compact-card mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 h5">Itens Disponíveis neste Estabelecimento</h5>
        <span class="badge bg-primary rounded-pill">{{ $itensEmEstabelecimento->count() }} {{ $itensEmEstabelecimento->count() == 1 ? 'item' : 'itens' }}</span>
    </div>
    <div class="card-body p-3">
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3">
            @foreach($itensEmEstabelecimento as $item)
            <div class="col">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition-all" style="max-width: 240px;">
                    <div class="position-relative" style="height: 140px; overflow: hidden;">
                        @if($item->fotos && $item->fotos->count() > 0)
                            <img src="{{ asset('storage/' . $item->fotos[0]->caminho) }}" 
                                 alt="{{ $item->descricao }}" 
                                 class="img-fluid w-100 h-100"
                                 style="object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                <i class="fas fa-image fa-2x text-muted"></i>
                            </div>
                        @endif
                        <span class="position-absolute top-0 start-0 badge bg-{{ $item->tipo == 'achado' ? 'success' : 'warning' }} m-1">
                            {{ ucfirst($item->tipo) }}
                        </span>
                    </div>
                    <div class="card-body p-3">
                        <h6 class="card-title fw-bold mb-2 text-truncate" title="{{ $item->descricao }}">
                            {{ $item->descricao }}
                        </h6>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-tag text-muted me-2"></i>
                            <small class="text-muted text-truncate">{{ $item->categoria->nome_categoria }}</small>
                        </div>
                        <a href="{{ route('mapa.item', $item->id) }}" class="btn btn-sm btn-primary w-100 py-2">
                            <i class="fas fa-info-circle me-1"></i> Detalhes
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
</div>

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async defer></script>
<script>
    function initMap() {
        @if($parceiro->localizacao && $parceiro->localizacao->latitude && $parceiro->localizacao->longitude)
            const parceiroLatLng = { 
                lat: {{ $parceiro->localizacao->latitude }}, 
                lng: {{ $parceiro->localizacao->longitude }} 
            };
            
            const map = new google.maps.Map(document.getElementById("parceiro-map"), {
                zoom: 15,
                center: parceiroLatLng,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: false,
                styles: [
                    {
                        "featureType": "poi",
                        "stylers": [
                            { "visibility": "off" }
                        ]
                    }
                ]
            });
            
            new google.maps.Marker({
                position: parceiroLatLng,
                map: map,
                title: "{{ $parceiro->nome_estabelecimento }}",
                icon: {
                    url: "{{ asset('img/marker-store.png') }}",
                    scaledSize: new google.maps.Size(40, 40)
                }
            });
        @endif
    }
</script>
@endpush
@endsection