@extends('admin.dashboard')

@section('content')
<div class="container">
    <!-- Verifica se é uma edição ou criação -->
    @if(isset($categoria))
        <h1>Editar Categoria</h1>
        <form action="{{ route('atualizar-categoria', $categoria->id) }}" method="POST">
        @method('PUT') <!-- Método HTTP para atualização -->
    @else
        <h1>Registrar Nova Categoria</h1>
        <form action="{{ route('registrar-categoria') }}" method="POST">
    @endif

    @csrf
    <div class="form-group">
        <label for="nome_categoria">Nome da Categoria</label>
        <input type="text" class="form-control" id="nome_categoria" name="nome_categoria"
               value="{{ isset($categoria) ? $categoria->nome_categoria : old('nome_categoria') }}" required>
    </div>
    <button type="submit" class="btn btn-primary">
        {{ isset($categoria) ? 'Atualizar' : 'Registrar' }}
    </button>
    </form>
</div>
@endsection