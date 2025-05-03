@if($item->status === 'aprovado' && !$item->parceiro_id)
<!-- Modal Enviar para Parceiro -->
<div class="modal fade" id="enviarParaParceiroModal-{{ $item->id }}" tabindex="-1" aria-labelledby="enviarParaParceiroModalLabel-{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="enviarParaParceiroModalLabel-{{ $item->id }}">
                    <i class="fas fa-store me-2"></i>Enviar Item para Ponto de Coleta
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('item.enviar-para-parceiro', ['item' => $item->id]) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <div id="map-{{ $item->id }}" style="height: 300px;" class="mb-3 rounded shadow-sm"></div>
                        <label for="parceiro_id-{{ $item->id }}" class="form-label fw-bold">
                            <i class="fas fa-map-marker-alt me-2"></i>Selecione o Ponto de Coleta
                        </label>
                        <select class="form-select" name="parceiro_id" id="parceiro_id-{{ $item->id }}" required>
                            <option value="">Selecione um ponto de coleta</option>
                            @foreach($parceiros as $parceiro)
                                <option value="{{ $parceiro->id }}" 
                                        data-lat="{{ $parceiro->localizacao->latitude }}" 
                                        data-lng="{{ $parceiro->localizacao->longitude }}">
                                    {{ $parceiro->nome_estabelecimento }} - {{ $parceiro->localizacao->endereco }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Escolha um ponto de coleta próximo para facilitar a entrega do item.</div>
                    </div>
                    <div class="mb-3">
                        <label for="observacoes-{{ $item->id }}" class="form-label fw-bold">
                            <i class="fas fa-comment-alt me-2"></i>Observações (opcional)
                        </label>
                        <textarea class="form-control" name="observacoes" id="observacoes-{{ $item->id }}" rows="3" 
                                  placeholder="Informe detalhes adicionais sobre o envio, se necessário..."></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-paper-plane me-2"></i>Confirmar Envio
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
