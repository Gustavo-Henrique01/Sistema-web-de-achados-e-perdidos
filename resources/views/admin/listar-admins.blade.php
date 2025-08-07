@extends('admin.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-user-shield me-2"></i>Administradores</h2>
        <a href="{{ route('admin.cadastrar-admin') }}" class="btn btn-primary">
            <i class="fas fa-user-plus me-1"></i> Novo Administrador
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if($admins->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-user-shield fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Nenhum administrador cadastrado</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>CPF</th>
                                <th>Telefone</th>
                                <th>Status</th>
                                <th>Data de Cadastro</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admins as $admin)
                                <tr>
                                    <td>
                                        <div class="avatar-table">
                                            @if($admin->avatar || $admin->foto)
                                                <img src="{{ asset('storage/'.($admin->avatar ?? $admin->foto)) }}" alt="Foto do Perfil">
                                            @else
                                                <div class="avatar-placeholder">
                                                    <i class="fas fa-user-shield"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>{{ $admin->cpf }}</td>
                                    <td>{{ $admin->telefone }}</td>
                                    <td>
                                        <span class="badge bg-{{ $admin->ativo ? 'success' : 'danger' }}">
                                            {{ $admin->ativo ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($admin->created_at)->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    onclick="toggleAdminStatus({{ $admin->id }}, {{ $admin->ativo ? 'false' : 'true' }})">
                                                <i class="fas fa-{{ $admin->ativo ? 'ban' : 'check' }}"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteAdmin({{ $admin->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Mostrando {{ $admins->firstItem() ?? 0 }} a {{ $admins->lastItem() ?? 0 }} de {{ $admins->total() }} administradores
                    </div>
                    <div>
                        {{ $admins->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .avatar-table {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid #fff;
        box-shadow: 0 0 5px rgba(0,0,0,0.1);
    }
    .avatar-table img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .avatar-placeholder {
        width: 100%;
        height: 100%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .avatar-placeholder i {
        font-size: 1rem;
        color: #adb5bd;
    }
    /* Estilização da paginação */
    .pagination {
        margin: 0;
        padding: 0;
    }
    .page-item {
        margin: 0 2px;
    }
    .page-link {
        border-radius: 4px;
        border: none;
        padding: 8px 12px;
        color: #0d6efd;
        background: #f8f9fa;
        transition: all 0.2s;
    }
    .page-link:hover {
        background: #e9ecef;
        color: #0a58ca;
    }
    .page-item.active .page-link {
        background: #0d6efd;
        color: white;
    }
    .page-item.disabled .page-link {
        background: #f8f9fa;
        color: #adb5bd;
        cursor: not-allowed;
    }
</style>

<script>
    function toggleAdminStatus(adminId, newStatus) {
        if (confirm('Tem certeza que deseja ' + (newStatus ? 'ativar' : 'desativar') + ' este administrador?')) {
            fetch(`/admin/usuario/${adminId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ ativo: newStatus })
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    throw new Error('Erro ao atualizar status');
                }
            })
            .catch(error => {
                alert('Erro ao atualizar status do administrador');
            });
        }
    }

    function deleteAdmin(adminId) {
        if (confirm('Tem certeza que deseja excluir este administrador?')) {
            fetch(`/admin/usuario/${adminId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                }
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    throw new Error('Erro ao excluir administrador');
                }
            })
            .catch(error => {
                alert('Erro ao excluir administrador');
            });
        }
    }
</script>
@endsection 