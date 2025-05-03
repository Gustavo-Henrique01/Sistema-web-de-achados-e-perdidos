<!-- Modal de Confirmação de Exclusão de Conta -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteAccountModalLabel"><i class="fas fa-exclamation-triangle me-2"></i>Excluir Conta</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">Você tem certeza que deseja excluir sua conta?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Importante:</strong> Sua conta será desativada e programada para exclusão em 30 dias. Durante esse período, você poderá cancelar a exclusão a qualquer momento.
                </div>
                <p class="text-muted small">Todos os seus dados e itens cadastrados ficarão indisponíveis após a exclusão definitiva.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('usuario.desativar-conta') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
                </form>
            </div>
        </div>
    </div>
</div>
