
@extends('admin.dashboard')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="container mt-4">
    <h2>Itens cadastrados por {{ $usuario->nome }} (ID: {{ $usuario->id }})</h2>
    <a href="{{ route('admin.itens.pendentes') }}" class="btn btn-secondary mb-3">Voltar para a listagem geral</a>
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
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
