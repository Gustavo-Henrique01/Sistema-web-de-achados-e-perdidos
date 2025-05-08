<!-- Perfil do Usuário -->
<div class="profile-card">
    <div class="profile-header">
        <h4 class="mb-0">Perfil do Usuário</h4>
        <p class="text-white-50">Membro desde {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</p>
    </div>
    
    <!-- Avatar do Usuário -->
    <div class="profile-avatar">
        @if ($user->foto)
            <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto do Usuário">
        @else
            <span class="text-muted">{{ substr($user->name, 0, 1) }}</span>
        @endif
    </div>
    
    <!-- Informações do Usuário -->
    <div class="profile-info">
        <h4 class="text-center mb-4 text-truncate" title="{{ $user->name }}">{{ $user->name }}</h4>
        
        <div class="profile-stats">
            <div class="stat-item">
                <div class="row">
                    <div class="col-5"><strong>Email:</strong></div>
                    <div class="col-7 text-end text-truncate" title="{{ $user->email }}">{{ $user->email }}</div>
                </div>
            </div>
            
            <div class="stat-item">
                <div class="row">
                    <div class="col-5"><strong>Telefone:</strong></div>
                    <div class="col-7 text-end text-truncate" title="{{ $user->telefone ?: 'Não informado' }}">{{ $user->telefone ?: 'Não informado' }}</div>
                </div>
            </div>
            
            <div class="stat-item">
                <div class="row">
                    <div class="col-5"><strong>CPF:</strong></div>
                    <div class="col-7 text-end text-truncate" title="{{ $user->cpf ?: 'Não informado' }}">{{ $user->cpf ?: 'Não informado' }}</div>
                </div>
            </div>
            
            <div class="stat-item">
                <div class="row">
                    <div class="col-5"><strong>Status:</strong></div>
                    <div class="col-7 text-end">
                        <span class="badge {{ $user->ativo ? 'bg-success' : 'bg-danger' }}">
                            {{ $user->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="stat-item">
                <div class="row">
                    <div class="col-5"><strong>Função:</strong></div>
                    <div class="col-7 text-end text-truncate" title="{{ $user->role->value }}">{{ $user->role->value }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Botões de Ação -->
    <div class="profile-stats">
        <div class="d-grid gap-2">
            <a href="{{ route('usuario.edit-profile') }}" class="btn btn-primary">
                <i class="fas fa-user-edit me-2"></i>Editar Perfil
            </a>
            <a href="{{ url('/chatify') }}" class="btn btn-success">
                <i class="fas fa-comments me-2"></i>Mensagens
            </a>
            
            @if($user->ativo)
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                    <i class="fas fa-user-times me-2"></i>Excluir Conta
                </button>
            @else
                <form action="{{ route('usuario.reativar-conta') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-user-check me-2"></i>Cancelar Exclusão da Conta
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<!-- Estatísticas do Usuário -->
<div class="profile-card">
    <div class="profile-header">
        <h4 class="mb-0">Estatísticas</h4>
    </div>
    
    <div class="profile-info p-3">
        <div class="row text-center">
            <div class="col-6">
                <h4>{{ $user->itens->count() }}</h4>
                <p class="text-muted mb-0">Itens cadastrados</p>
            </div>
            <div class="col-6">
                <h4>{{ $user->itens->where('status', 'aprovado')->count() }}</h4>
                <p class="text-muted mb-0">Itens aprovados</p>
            </div>
        </div>
    </div>
</div>

<!-- Mensagens Recentes -->
<div class="profile-card">
    <div class="profile-header">
        <h4 class="mb-0">Mensagens Recentes</h4>
    </div>
    
    <div class="list-group list-group-flush">
        <a href="{{ url('/chatify') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-inbox me-2 text-primary"></i>
                <span>Ver todas as mensagens</span>
            </div>
            <i class="fas fa-chevron-right"></i>
        </a>
    </div>
</div>
