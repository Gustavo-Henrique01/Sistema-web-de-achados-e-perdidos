@extends('usuario.home')

@section('content')
<!-- Alertas e mensagens do sistema -->
@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <div class="me-3">
                <i class="fas fa-exclamation-triangle fa-2x"></i>
            </div>
            <div>
                <h5 class="alert-heading mb-1">Atenção!</h5>
                <p class="mb-0">{{ session('warning') }}</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<<<<<<< HEAD
    <!-- Bootstrap JS e dependências -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
@endsection
=======
<!-- Importação de bibliotecas externas -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<!-- Estilos CSS -->
@include('usuario.partials.styles')

<!-- Cabeçalho da página -->
<div class="bg-light rounded-3 p-4 mb-4 shadow-sm">
    <div class="d-flex align-items-center">
        <div class="me-3">
            <i class="fas fa-user-circle fa-3x text-primary"></i>
        </div>
        <div>
            <h4 class="mb-1">Meu Perfil</h4>
            <p class="text-muted mb-0">Gerencie seus dados pessoais e itens cadastrados</p>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <!-- Coluna do Perfil do Usuário -->
        <div class="col-lg-4 mb-4">
            @include('usuario.partials.profile-card')
        </div>
        
        <!-- Coluna dos Itens Cadastrados -->
        <div class="col-lg-8">
            @include('usuario.partials.items-list')
        </div>
    </div>
</div>
<!-- Modais para cada item -->
@foreach($user->itens as $item)
    @include('usuario.partials.modals.enviar-parceiro-modal', ['item' => $item])
    @include('usuario.partials.modals.devolver-item-modal', ['item' => $item])
@endforeach

<!-- Modal de Confirmação de Exclusão de Conta -->
@include('usuario.partials.modals.delete-account-modal')
<!-- Scripts -->
@push('scripts')
    @include('usuario.partials.scripts')
@endpush
@endsection
   
>>>>>>> funcionalidades
