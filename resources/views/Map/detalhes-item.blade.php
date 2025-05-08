@extends('layouts.parceiro')

@section('title', 'Detalhes do Item')

@section('content')
<div class="container-fluid px-3 px-md-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Detalhes do Item</h1>
        <a href="{{ route('mapa.mostrar') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Voltar para o Mapa
        </a>
    </div>

    <div class="row g-3">
        <!-- Informações do Item -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="row g-3">
                        <!-- Imagem do Item -->
                        <div class="col-md-4 col-sm-5">
                            <div class="position-relative">
                                @if($item->fotos && $item->fotos->count() > 0)
                                    <img src="{{ asset('storage/' . $item->fotos[0]->caminho) }}" 
                                         alt="{{ $item->descricao }}" 
                                         class="img-fluid rounded w-100"
                                         style="height: 250px; object-fit: contain; background-color: #f8f9fa;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                         style="height: 250px;">
                                        <i class="fas fa-image fa-2x text-muted"></i>
                                    </div>
                                @endif
                                <span class="position-absolute top-0 start-0 badge bg-{{ $item->tipo == 'achado' ? 'success' : 'warning' }} m-2">
                                    {{ ucfirst($item->tipo) }}
                                </span>
                            </div>
                            
                            <!-- Outras Fotos (se houver) -->
                            @if($item->fotos && $item->fotos->count() > 1)
                            <div class="mt-2">
                                <small class="text-muted d-block mb-1">Mais Fotos</small>
                                <div class="row g-1">
                                    @foreach($item->fotos->skip(1) as $foto)
                                    <div class="col-3">
                                        <img src="{{ asset('storage/' . $foto->caminho) }}" 
                                             alt="Foto adicional" 
                                             class="img-thumbnail"
                                             style="width: 100%; height: 60px; object-fit: cover;">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            <!-- Informações de Contato (Mobile) -->
                            <div class="mt-3 d-md-none">
                                <div class="card bg-light border-0">
                                    <div class="card-body p-2">
                                        <h6 class="card-title small fw-bold mb-2">Informações de Contato</h6>
                                        
                                        @if($item->tipo == 'achado' && $item->parceiro)
                                            <div class="d-flex align-items-center small mb-1">
                                                <i class="fas fa-store text-primary me-2"></i>
                                                <span>{{ $item->parceiro->nome_estabelecimento }}</span>
                                            </div>
                                            <div class="d-flex align-items-center small mb-1">
                                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                <span>{{ $item->parceiro->localizacao->endereco }}</span>
                                            </div>
                                            <div class="d-flex align-items-center small mb-1">
                                                <i class="fas fa-clock text-primary me-2"></i>
                                                <span>{{ $item->parceiro->horario_funcionamento }}</span>
                                            </div>
                                            <div class="d-flex align-items-center small">
                                                <i class="fas fa-phone-alt text-primary me-2"></i>
                                                <span>{{ $item->parceiro->telefone_comercial }}</span>
                                            </div>
                                        @elseif($item->usuario)
                                            <div class="d-flex align-items-center small mb-1">
                                                <i class="fas fa-user text-primary me-2"></i>
                                                <span>{{ $item->usuario->name }}</span>
                                            </div>
                                            <div class="d-flex align-items-center small">
                                                <i class="fas fa-envelope text-primary me-2"></i>
                                                <span>{{ $item->usuario->email }}</span>
                                            </div>
                                        @else
                                            <p class="small mb-0">Nenhuma informação de contato disponível.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Detalhes do Item -->
                        <div class="col-md-8 col-sm-7">
                            <div class="d-flex flex-wrap align-items-center mb-2 gap-2">
                                <h5 class="mb-0 me-auto">{{ $item->descricao }}</h5>
                                <span class="badge bg-{{ $item->status == 'em_estabelecimento' ? 'info' : 'secondary' }}">
                                    {{ str_replace('_', ' ', ucfirst($item->status)) }}
                                </span>
                            </div>
                            
                            <div class="row g-2 mb-3">
                                <div class="col-sm-6 col-lg-4">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-tag text-primary me-2 mt-1"></i>
                                        <div>
                                            <small class="text-muted d-block">Categoria</small>
                                            <span>{{ $item->categoria->nome_categoria }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-calendar-alt text-primary me-2 mt-1"></i>
                                        <div>
                                            <small class="text-muted d-block">Data {{ $item->tipo == 'achado' ? 'Encontrado' : 'Perdido' }}</small>
                                            <span>
                                                @if($item->tipo == 'achado')
                                                    @if(is_string($item->data_encontrado))
                                                        {{ \Carbon\Carbon::parse($item->data_encontrado)->format('d/m/Y') }}
                                                    @else
                                                        {{ $item->data_encontrado->format('d/m/Y') }}
                                                    @endif
                                                @else
                                                    @if(is_string($item->data_perdido))
                                                        {{ \Carbon\Carbon::parse($item->data_perdido)->format('d/m/Y') }}
                                                    @else
                                                        {{ $item->data_perdido->format('d/m/Y') }}
                                                    @endif
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($item->local_encontrado || $item->local_perdido)
                                <div class="col-sm-6 col-lg-4">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-map-marker-alt text-primary me-2 mt-1"></i>
                                        <div>
                                            <small class="text-muted d-block">Local {{ $item->tipo == 'achado' ? 'Encontrado' : 'Perdido' }}</small>
                                            <span>{{ $item->tipo == 'achado' ? $item->local_encontrado : $item->local_perdido }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                @if($item->tipo == 'achado' && $item->parceiro)
                                <div class="col-sm-6 col-lg-4">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-store text-primary me-2 mt-1"></i>
                                        <div>
                                            <small class="text-muted d-block">Estabelecimento</small>
                                            <a href="{{ route('mapa.parceiro', $item->parceiro->id) }}" class="text-decoration-none">
                                                {{ $item->parceiro->nome_estabelecimento }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Informações do Usuário (Desktop) -->
                            @if($item->tipo == 'achado' && $item->usuario)
                            <div class="mb-0 d-none d-md-block">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-user text-primary me-2 mt-1"></i>
                                    <div>
                                        <small class="text-muted d-block">Informações de Contato</small>
                                        <span>{{ $item->usuario->name }}</span>
                                        <small class="text-muted d-block">{{ $item->usuario->email }}</small>
                                        @if($item->usuario->telefone)
                                            <small class="text-muted d-block">{{ $item->usuario->telefone }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Mapa de Localização -->
                        @if($item->localizacao && $item->localizacao->latitude && $item->localizacao->longitude)
                        <div class="col-md-3 d-none d-md-block">
                            <div id="item-map" style="height: 180px; width: 100%; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"></div>
                            <div class="text-center mt-1">
                                <small class="text-muted">Localização no Mapa</small>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Galeria de Imagens (Desktop) -->
                    @if($item->fotos && $item->fotos->count() > 1)
                    <div class="mt-3 d-none d-md-block">
                        <h6 class="small fw-bold mb-2">Galeria de Imagens</h6>
                        <div class="row g-2">
                            @foreach($item->fotos as $foto)
                            <div class="col-2 col-lg-1">
                                <a href="{{ asset('storage/' . $foto->caminho) }}" target="_blank" class="d-block">
                                    <img src="{{ asset('storage/' . $foto->caminho) }}" 
                                         alt="Foto do item" 
                                         class="img-fluid rounded w-100"
                                         style="height: 60px; object-fit: cover;">
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <!-- Mapa (Mobile) -->
                    @if($item->localizacao && $item->localizacao->latitude && $item->localizacao->longitude)
                    <div class="mt-3 d-md-none">
                        <h6 class="small fw-bold mb-2">Localização no Mapa</h6>
                        <div id="item-map-mobile" style="height: 150px; width: 100%; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"></div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initItemMap" async defer></script>
<script>
    function initItemMap() {
        @if(($item->tipo == 'achado' && $item->parceiro && $item->parceiro->localizacao && $item->parceiro->localizacao->latitude) || 
            ($item->localizacao && $item->localizacao->latitude && $item->localizacao->longitude))
            const itemLatLng = { 
                lat: {{ $item->tipo == 'achado' && $item->parceiro && $item->parceiro->localizacao ? $item->parceiro->localizacao->latitude : $item->localizacao->latitude }}, 
                lng: {{ $item->tipo == 'achado' && $item->parceiro && $item->parceiro->localizacao ? $item->parceiro->localizacao->longitude : $item->localizacao->longitude }} 
            };
            
            // Inicializar o mapa
            const map = new google.maps.Map(document.getElementById("item-map"), {
                zoom: 15,
                center: itemLatLng,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: false
            });
            
            // Adicionar marcador
            const marker = new google.maps.Marker({
                position: itemLatLng,
                map: map,
                title: '{{ $item->descricao }}',
                icon: {
                    url: "{{ asset('img/marker-' . ($item->tipo == 'achado' ? 'found' : 'lost') . '.png') }}",
                    scaledSize: new google.maps.Size(40, 40)
                }
            });
        @endif
    }
</script>
@endpush
@endsection
