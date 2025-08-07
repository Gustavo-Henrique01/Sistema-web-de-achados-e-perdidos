@extends('admin.dashboard')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="container mt-4">
    <h2>Perfil do Usuário</h2>
    <div class="card mb-4">
        <div class="card-body text-center">
            <img src="{{ asset('storage/'.$usuario->foto) }}" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;" alt="Foto do usuário">
            <h4>{{ $usuario->nome }}</h4>
            <p>Email: {{ $usuario->email }}</p>
            <p>Telefone: {{ $usuario->telefone }}</p>
            <p>CPF: {{ $usuario->cpf }}</p>
            <p>Status: {{ $usuario->ativo ? 'Ativo' : 'Inativo' }}</p>
        </div>
    </div>
    <h3>Itens cadastrados por {{ $usuario->nome }} (ID: {{ $usuario->id }})</h3>
    <a href="{{ route('admin.listar-itens-all') }}" class="btn btn-secondary mb-3">Voltar para a listagem geral</a>
    <div class="row">
        @foreach ($itens as $item)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <img src="{{ asset('storage/'.$item->foto) }}" class="card-img-top" alt="Foto do item">
                    <div class="card-body">
                        <h5 class="card-title">Categoria: {{ $item->categoria }}</h5>
                        <p class="card-text">Descrição: {{ $item->descricao }}</p>
                        <p class="card-text">Tipo: {{ $item->tipo }}</p>
                        <p class="card-text">
                            <small class="text-muted">Registrado em: {{ \Carbon\Carbon::parse($item->data_registro)->format('d/m/Y') }}</small>
                        </p>
                        <p class="card-text">Status: {{ $item->status }}</p>
                    </div>
                    <div class="card-footer text-center">
                        <form action="{{ route('admin.itens-aprovar', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm me-2">
                                <i class="bi bi-check-circle"></i> Aprovar
                            </button>
                        </form>
                        <form action="{{ route('admin.itens-rejeitar', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bi bi-x-circle"></i> Rejeitar
                            </button>
                        </form>
                    </div>
                    <p class="mt-3 text-muted">Carregando detalhes do item...</p>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos do container */
    .container-fluid {
        width: 100%;
        min-height: 100vh;
    }
    
    /* Make main content area expand */
    .content-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
        width: 100%;
    }
    
    /* Card should expand to fill available space */
    .card.shadow-sm {
        flex: 1;
        display: flex;
        flex-direction: column;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    
    /* Card body should expand */
    .card-body {
        flex: 1 1 auto;
        min-height: 0;
    }
    
    /* Estilos do avatar */
    .user-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto;
        border: 3px solid #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .avatar-placeholder {
        width: 100%;
        height: 100%;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #6c757d;
    }
    
    /* Estilos dos cards de estatística */
    .stat-icon {
        opacity: 0.9;
        transition: all 0.3s ease;
    }
    
    .card:hover .stat-icon {
        transform: scale(1.1);
    }
    
    /* Estilos dos botões de filtro */
    .btn-group.flex-wrap {
        gap: 0.25rem;
    }
    
    .btn-group .btn {
        flex: 1 0 auto;
        min-width: 120px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    /* Estilos dos cards de itens */
    .item-card .card {
        transition: all 0.3s ease;
    }
    
    .item-card:hover .card {
        transform: translateY(-8px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1) !important;
    }
    
    /* Estilos para os itens de informação */
    .info-item {
        padding: 0.5rem 0;
        border-bottom: 1px solid #f0f0f0;
        font-weight: 500;
        transition: background-color 0.2s ease;
    }
    
    .info-item:hover {
        background-color: #f8f9fa;
        border-radius: 4px;
        padding-left: 5px;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }

    /* Melhorias para imagens de itens */
    .card-img-top {
        transition: all 0.3s ease;
    }
    
    .item-card:hover .card-img-top {
        transform: scale(1.03);
    }
    
    /* Ajustes para responsividade */
    @media (max-width: 768px) {
        .d-flex.flex-column.flex-md-row {
            gap: 1rem;
        }
        
        .btn-group.flex-wrap {
            margin-top: 1rem;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtro de itens por status
    const filterButtons = document.querySelectorAll('[data-filter]');
    const itemCards = document.querySelectorAll('.item-card');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Atualiza botões ativos
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filtra os cards
            itemCards.forEach(card => {
                if (filter === 'todos' || card.dataset.status === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});

function viewItemDetails(itemId) {
    const modal = new bootstrap.Modal(document.getElementById('itemDetailsModal'));
    const modalBody = document.querySelector('#itemDetailsModal .modal-body');
    
    // Mostra loading
    modalBody.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';
    modal.show();
    
    // Carrega detalhes via AJAX
    fetch(`/admin/itens/${itemId}/detalhes`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro ao carregar detalhes do item');
            }
            return response.text();
        })
        .then(html => {
            modalBody.innerHTML = html;
        })
        .catch(error => {
            modalBody.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-circle fa-2x text-danger mb-3"></i>
                    <p class="text-danger mb-0">Erro ao carregar detalhes do item</p>
                </div>
            `;
        });
}

function toggleUserStatus(userId, newStatus) {
    if (confirm(`Tem certeza que deseja ${newStatus ? 'ativar' : 'desativar'} este usuário?`)) {
        fetch(`/admin/usuario/${userId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro ao atualizar status do usuário');
            }
            window.location.reload();
        })
        .catch(error => {
            alert('Erro ao atualizar status do usuário');
        });
    }
}
</script>

@endsection