@extends('usuario.home')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Coluna Principal -->
        <div class="col-md-8">
            <!-- Cabeçalho do Item -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h2 class="card-title mb-0">{{ $item->titulo }}</h2>
                        <span class="badge bg-{{ $item->status === 'aprovado' ? 'success' : ($item->status === 'pendente' ? 'warning' : 'danger') }} fs-6">
                            {{ ucfirst($item->status) }}
                        </span>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Categoria</p>
                            <p class="mb-3">
                                <i class="fas fa-tag me-2"></i>
                                {{ $item->categoria->nome_categoria }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Tipo</p>
                            <p class="mb-3">
                                <i class="fas fa-{{ $item->tipo === 'achado' ? 'hand-holding' : 'search' }} me-2"></i>
                                {{ ucfirst($item->tipo) }}
                            </p>
                        </div>
                    </div>

                    <p class="mb-1 text-muted">Descrição</p>
                    <p class="mb-4">{{ $item->descricao }}</p>

                    <p class="mb-1 text-muted">Localização</p>
                    <p class="mb-3">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        {{ $item->localizacao->endereco }}
                    </p>

                    @if($item->parceiro)
                    <div class="alert alert-info mb-0">
                        <h6 class="alert-heading mb-2">
                            <i class="fas fa-store me-2"></i>
                            Item em Ponto de Coleta
                        </h6>
                        <p class="mb-1"><strong>{{ $item->parceiro->nome_estabelecimento }}</strong></p>
                        <p class="mb-0 small">{{ $item->parceiro->localizacao->endereco }}</p>
                    </div>
                    @endif

                    @if($item->status === 'aprovado' && !$item->parceiro_id)
                    <div class="mt-4">
                        <button type="button" 
                                class="btn btn-primary" 
                                data-bs-toggle="modal" 
                                data-bs-target="#enviarParaParceiroModal">
                            <i class="fas fa-store me-2"></i>Enviar para Ponto de Coleta
                        </button>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Fotos do Item -->
            @if($item->fotos->count() > 0)
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-images me-2"></i>Fotos do Item
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($item->fotos as $foto)
                        <div class="col-md-4">
                            <img src="{{ asset('storage/' . $foto->caminho) }}" 
                                 alt="Foto do item" 
                                 class="img-fluid rounded">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Coluna Lateral -->
        <div class="col-md-4">
            <!-- Informações de Registro -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar me-2"></i>Informações de Registro
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-1 text-muted">Data de Registro</p>
                    <p class="mb-3">{{ $item->created_at->format('d/m/Y H:i') }}</p>

                    @if($item->data_perdido)
                    <p class="mb-1 text-muted">Data em que foi perdido</p>
                    <p class="mb-3">{{ \Carbon\Carbon::parse($item->data_perdido)->format('d/m/Y') }}</p>
                    @endif

                    @if($item->data_encontrado)
                    <p class="mb-1 text-muted">Data em que foi encontrado</p>
                    <p class="mb-0">{{ \Carbon\Carbon::parse($item->data_encontrado)->format('d/m/Y') }}</p>
                    @endif
                </div>
            </div>

            <!-- Informações do Registrante -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>Registrado por
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-1">{{ $item->usuario->name }}</p>
                    <p class="mb-0 text-muted small">{{ $item->usuario->email }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Enviar para Parceiro -->
@if($item->status === 'aprovado' && !$item->parceiro_id)
<div class="modal fade" id="enviarParaParceiroModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enviar Item para Ponto de Coleta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('item.enviar-para-parceiro', $item) }}" method="POST" id="enviarParaParceiroForm">
                @csrf
                <div class="modal-body">
                    <!-- Mapa para selecionar parceiro -->
                    <div class="mb-4">
                        <label class="form-label">Localizar Pontos de Coleta no Mapa</label>
                        <div id="mapaParceiros" style="height: 300px;" class="mb-3"></div>
                    </div>

                    <!-- Select para escolher o parceiro -->
                    <div class="mb-3">
                        <label class="form-label">Selecione o Ponto de Coleta</label>
                        <select name="parceiro_id" class="form-select" required>
                            <option value="">Selecione...</option>
                            @foreach($parceiros as $parceiro)
                                <option value="{{ $parceiro->id }}" 
                                        data-lat="{{ $parceiro->localizacao->latitude }}"
                                        data-lng="{{ $parceiro->localizacao->longitude }}">
                                    {{ $parceiro->nome_estabelecimento }} - 
                                    {{ $parceiro->localizacao->endereco }}
                                    ({{ $parceiro->horario_funcionamento }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Campo para observações -->
                    <div class="mb-3">
                        <label class="form-label">Observações (opcional)</label>
                        <textarea name="observacoes" 
                                  class="form-control" 
                                  rows="3" 
                                  placeholder="Adicione informações relevantes sobre a entrega..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        Confirmar Envio
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('enviarParaParceiroModal');
    const form = document.getElementById('enviarParaParceiroForm');
    const select = document.querySelector('select[name="parceiro_id"]');
    
    if (!modal || !form || !select) return;

    let map = null;
    const markers = {};

    // Inicializa o mapa quando o modal é aberto
    modal.addEventListener('shown.bs.modal', function() {
        if (!map) {
            map = L.map('mapaParceiros').setView([-20.4697, -54.6201], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
            
            // Adicionar marcadores para cada parceiro
            const parceiros = @json($parceiros);
            parceiros.forEach(parceiro => {
                const marker = L.marker([
                    parceiro.localizacao.latitude, 
                    parceiro.localizacao.longitude
                ]).addTo(map);
                
                marker.bindPopup(`
                    <strong>${parceiro.nome_estabelecimento}</strong><br>
                    ${parceiro.localizacao.endereco}<br>
                    <small>${parceiro.horario_funcionamento}</small>
                `);
                
                markers[parceiro.id] = marker;
            });
        }
    });

    // Atualiza o mapa quando selecionar um parceiro
    select.addEventListener('change', function() {
        if (!map) return;
        
        const option = this.options[this.selectedIndex];
        if (option.value) {
            const lat = option.dataset.lat;
            const lng = option.dataset.lng;
            map.setView([lat, lng], 15);
            markers[option.value].openPopup();
        }
    });

    // Manipula o envio do formulário
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const parceiroId = select.value;
        const observacoes = form.querySelector('[name="observacoes"]').value;
        
        if (!parceiroId) {
            alert('Por favor, selecione um ponto de coleta');
            return;
        }

        console.log('Enviando formulário:', {
            parceiro_id: parceiroId,
            observacoes: observacoes
        });

        // Envia o formulário
        this.submit();
    });
});
</script>
@endpush
@endif
@endsection 