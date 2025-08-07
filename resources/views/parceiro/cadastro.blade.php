@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Cadastro de Parceiro') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('parceiro.cadastro.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="nome_estabelecimento" class="col-md-4 col-form-label text-md-end">{{ __('Nome do Estabelecimento') }}</label>

                            <div class="col-md-6">
                                <input id="nome_estabelecimento" type="text" class="form-control @error('nome_estabelecimento') is-invalid @enderror" name="nome_estabelecimento" value="{{ old('nome_estabelecimento') }}" required autocomplete="nome_estabelecimento" autofocus>

                                @error('nome_estabelecimento')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="cnpj" class="col-md-4 col-form-label text-md-end">{{ __('CNPJ') }}</label>

                            <div class="col-md-6">
                                <input id="cnpj" type="text" class="form-control @error('cnpj') is-invalid @enderror" name="cnpj" value="{{ old('cnpj') }}" required autocomplete="cnpj" placeholder="00.000.000/0000-00">

                                @error('cnpj')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="tipo_parceiro" class="col-md-4 col-form-label text-md-end">{{ __('Tipo de Parceiro') }}</label>

                            <div class="col-md-6">
                                <select id="tipo_parceiro" class="form-control @error('tipo_parceiro') is-invalid @enderror" name="tipo_parceiro" required>
                                    <option value="">Selecione o tipo</option>
                                    <option value="ponto_coleta" {{ old('tipo_parceiro') == 'ponto_coleta' ? 'selected' : '' }}>Ponto de Coleta</option>
                                    <option value="evento" {{ old('tipo_parceiro') == 'evento' ? 'selected' : '' }}>Evento</option>
                                    <option value="ambos" {{ old('tipo_parceiro') == 'ambos' ? 'selected' : '' }}>Ambos</option>
                                </select>

                                @error('tipo_parceiro')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Cadastrar') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Máscara para o CNPJ
        const cnpjInput = document.getElementById('cnpj');
        cnpjInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 14) value = value.slice(0, 14);
            
            if (value.length > 12) {
                value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
            } else if (value.length > 8) {
                value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})/, '$1.$2.$3/$4');
            } else if (value.length > 5) {
                value = value.replace(/^(\d{2})(\d{3})(\d{3})/, '$1.$2.$3');
            } else if (value.length > 2) {
                value = value.replace(/^(\d{2})(\d{3})/, '$1.$2');
            }
            
            e.target.value = value;
        });
    });
</script>
@endpush
@endsection 