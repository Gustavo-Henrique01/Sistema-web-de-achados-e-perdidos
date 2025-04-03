@extends('admin.dashboard')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Log de Ações Administrativas</h5>
            
            <!-- Filtros -->
            <form id="filterForm" class="d-flex gap-2">
                <select class="form-select form-select-sm" id="filterAction" name="acao" style="width: 150px;" onchange="this.form.submit()">
                    <option value="" {{ !$filtroAtual ? 'selected' : '' }}>Todas as ações</option>
                    <option value="aprovacao" {{ $filtroAtual === 'aprovacao' ? 'selected' : '' }}>Aprovações</option>
                    <option value="reprovacao" {{ $filtroAtual === 'reprovacao' ? 'selected' : '' }}>Reprovações</option>
                    <option value="exclusao" {{ $filtroAtual === 'exclusao' ? 'selected' : '' }}>Exclusões</option>
                </select>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Data/Hora</th>
                            <th>Administrador</th>
                            <th>Item</th>
                            <th>Ação</th>
                            <th>Status Anterior</th>
                            <th>Status Novo</th>
                            <th>Justificativa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($log->admin->foto)
                                            <img src="{{ asset('storage/'.$log->admin->foto) }}" 
                                                 class="rounded-circle me-2" 
                                                 style="width: 32px; height: 32px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-secondary text-white me-2 d-flex align-items-center justify-content-center" 
                                                 style="width: 32px; height: 32px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $log->admin->name }}</div>
                                            <small class="text-muted">{{ $log->admin->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.ver-detalhes-item', $log->item_id) }}" 
                                       class="text-decoration-none">
                                        #{{ $log->item_id }}
                                    </a>
                                </td>
                                <td>
                                    @if($log->acao === 'aprovacao')
                                        <span class="badge bg-success">Aprovação</span>
                                    @elseif($log->acao === 'reprovacao')
                                        <span class="badge bg-danger">Reprovação</span>
                                    @else
                                        <span class="badge bg-dark">Exclusão</span>
                                    @endif
                                </td>
                                <td>{{ ucfirst($log->status_anterior) }}</td>
                                <td>{{ ucfirst($log->status_novo) }}</td>
                                <td>
                                    @if($log->justificativa)
                                        <div class="text-wrap" style="max-width: 300px;">
                                            {{ $log->justificativa }}
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // O formulário será submetido automaticamente quando o select mudar
    // devido ao atributo onchange="this.form.submit()" no select
});
</script>
@endpush
@endsection 