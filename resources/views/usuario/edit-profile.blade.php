@extends('usuario.home')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Editar Perfil</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('usuario.update-profile') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                  <!-- Avatar e Foto -->
<div class="text-center mb-4">
    <div class="position-relative d-inline-block">
        <img id="profile-picture" 
             src="{{ $user->foto ? asset('storage/' . $user->foto) : asset('img/default-avatar.png') }}" 
             class="rounded-circle shadow-sm border border-light" 
             style="width: 150px; height: 150px; object-fit: cover; transition: transform 0.3s ease;"
             alt="Foto de perfil de {{ $user->name }}"
             onmouseover="this.style.transform='scale(1.05)'"
             onmouseout="this.style.transform='scale(1)'">
        
        <label for="foto" class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-2 cursor-pointer shadow-sm hover-bg-success" 
               style="transition: all 0.2s ease;">
            <i class="fas fa-camera text-white"></i>
            <span class="visually-hidden">Alterar foto</span>
        </label>
        
        <input type="file" id="foto" name="foto" class="d-none" accept="image/*" onchange="previewImage(this)">
    </div>
    <small class="text-muted d-block mt-2">Clique no ícone para alterar</small>
</div>                        <!-- Nome -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Telefone -->
                        <div class="mb-3">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" class="form-control @error('telefone') is-invalid @enderror" 
                                   id="telefone" name="telefone" value="{{ old('telefone', $user->telefone) }}" 
                                   placeholder="(00) 00000-0000">
                            @error('telefone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CPF (somente leitura) -->
                        <div class="mb-3">
                            <label for="cpf" class="form-label">CPF</label>
                            <input type="text" class="form-control" id="cpf" value="{{ $user->cpf }}" readonly>
                        </div>

                        <!-- Senha -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Nova Senha</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" placeholder="Deixe em branco para manter a senha atual">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirmação de Senha -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" 
                                   placeholder="Confirme a nova senha">
                        </div>

                        <!-- Botões -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('perfil-usuario') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Voltar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview da imagem ao selecionar
    document.getElementById('foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.querySelector('.rounded-circle').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    // Máscara para o telefone
    document.getElementById('telefone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 11) {
            value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        }
        e.target.value = value;
    });

    function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profile-picture').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection 