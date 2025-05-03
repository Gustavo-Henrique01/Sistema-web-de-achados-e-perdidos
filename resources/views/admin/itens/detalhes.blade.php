@extends('admin.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <div class="bg-white shadow-sm rounded-3 p-4 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2">
            <div class="mb-3 mb-md-0">
                <h2 class="fw-bold mb-0"><i class="fas fa-box-open me-2 text-primary"></i>Detalhes do Item</h2>
                <p class="text-muted mb-0">Informações completas do item #{{ $item->id }}</p>
            </div>
            <div>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary rounded-pill">
                    <i class="fas fa-arrow-left me-1"></i> Voltar
                </a>
            </div>
        </div>
    </div>
    
    @include('admin.partials.detalhes-item')
</div>
@endsection
