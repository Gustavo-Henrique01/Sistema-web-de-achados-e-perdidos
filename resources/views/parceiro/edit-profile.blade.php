@extends('layouts.parceiro')

@section('title', 'Editar Perfil')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">Editar Perfil</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('parceiro.update-profile') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-4">
                            <div class="col-md-3 text-center">
                                <div class="mb-3">
                                    @if($parceiro->logo)
                                        <img src="{{ asset('storage/' . $parceiro->logo) }}" 
                                             alt="Logo do estabelecimento" 
                                             class="img-thumbnail mb-3" 
                                             style="width: 150px; height: 150px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center mb-3" 
                                             style="width: 150px; height: 150px; border-radius: 5px; margin: 0 auto;">
                                            <i class="fas fa-store fa-3x text-secondary"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="mb-3">
                                        <label for="logo" class="form-label">Alterar Logo</label>
                                        <input type="file" class="form-control" id="logo" name="logo">
                                        <div class="form-text">Imagem quadrada recomendada. Máximo 2MB.</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-9">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="nome_estabelecimento" class="form-label">Nome do Estabelecimento</label>
                                        <input type="text" class="form-control" id="nome_estabelecimento" name="nome_estabelecimento" value="{{ old('nome_estabelecimento', $parceiro->nome_estabelecimento) }}" required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="telefone_comercial" class="form-label">Telefone Comercial</label>
                                        <input type="text" class="form-control" id="telefone_comercial" name="telefone_comercial" value="{{ old('telefone_comercial', $parceiro->telefone_comercial) }}" required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">E-mail</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="horario_funcionamento" class="form-label">Horário de Funcionamento</label>
                                        <input type="text" class="form-control" id="horario_funcionamento" name="horario_funcionamento" value="{{ old('horario_funcionamento', $parceiro->horario_funcionamento) }}">
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <label for="descricao" class="form-label">Descrição</label>
                                        <textarea class="form-control" id="descricao" name="descricao" rows="3">{{ old('descricao', $parceiro->descricao) }}</textarea>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Tipo de Parceiro</label>
                                        <div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="tipo_parceiro" id="tipo_ponto_coleta" value="ponto_coleta" {{ (old('tipo_parceiro', $parceiro->tipo_parceiro) == 'ponto_coleta') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="tipo_ponto_coleta">Ponto de Coleta</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="tipo_parceiro" id="tipo_evento" value="evento" {{ (old('tipo_parceiro', $parceiro->tipo_parceiro) == 'evento') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="tipo_evento">Evento</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="tipo_parceiro" id="tipo_ambos" value="ambos" {{ (old('tipo_parceiro', $parceiro->tipo_parceiro) == 'ambos') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="tipo_ambos">Ambos</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h5 class="mb-3">Alterar Senha</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="password" class="form-label">Nova Senha</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <div class="form-text">Deixe em branco para manter a senha atual.</div>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('parceiro.home') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Máscara para telefone
    document.addEventListener('DOMContentLoaded', function() {
        const telefoneInput = document.getElementById('telefone_comercial');
        
        telefoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length > 11) {
                value = value.substring(0, 11);
            }
            
            if (value.length > 0) {
                value = '(' + value;
                
                if (value.length > 3) {
                    value = value.substring(0, 3) + ') ' + value.substring(3);
                }
                
                if (value.length > 10) {
                    value = value.substring(0, 10) + '-' + value.substring(10);
                }
            }
            
            e.target.value = value;
        });
    });
</script>
@endpush