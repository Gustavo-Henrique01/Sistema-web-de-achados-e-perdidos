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
                    <form action="{{ route('usuario.update-profile') }}" method="POST" enctype="multipart/form-data" id="editProfileForm" novalidate>
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
        
        <input type="file" id="foto" name="foto" class="d-none" accept="image/jpeg, image/png, image/jpg, image/gif" onchange="previewImage(this)">
    </div>
    <small class="text-muted d-block mt-2">Clique no ícone para alterar</small>
    <small class="text-muted d-block">Formatos aceitos: JPG, JPEG, PNG, GIF (máx. 2MB)</small>
    <div class="feedback-error mt-2" id="foto-error">Selecione uma imagem válida nos formatos: JPG, JPEG, PNG ou GIF (máx. 2MB).</div>
</div>                        <!-- Nome -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required minlength="3">
                            <div class="feedback-error" id="name-error">O nome deve ter no mínimo 3 caracteres.</div>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            <div class="feedback-error" id="email-error">Digite um endereço de e-mail válido.</div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Telefone -->
                        <div class="mb-3">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" class="form-control @error('telefone') is-invalid @enderror" 
                                   id="telefone" name="telefone" value="{{ old('telefone', $user->telefone) }}" 
                                   placeholder="(00) 0000-0000" minlength="14" maxlength="14" required>
                            <div class="feedback-error" id="telefone-error">O telefone deve estar no formato (99) 9999-9999.</div>
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
                                   id="password" name="password" placeholder="Deixe em branco para manter a senha atual" minlength="5">
                            <div class="feedback-error" id="password-error">A senha deve ter no mínimo 5 caracteres.</div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirmação de Senha -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" 
                                   placeholder="Confirme a nova senha" minlength="5">
                            <div class="feedback-error" id="password-confirmation-error">As senhas não coincidem.</div>
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
<style>
    .feedback-error {
        color: #dc3545;
        font-size: 80%;
        margin-top: 0.25rem;
        display: none;
    }
</style>
<script>
    // Preview da imagem ao selecionar
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const fotoError = document.getElementById('foto-error');
            
            // Verifica o tipo do arquivo
            const fileType = file.type;
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            
            // Verifica o tamanho (máximo 2MB = 2 * 1024 * 1024 bytes)
            const maxSize = 2 * 1024 * 1024;
            
            if (!validTypes.includes(fileType)) {
                showError(input, fotoError);
                return;
            }
            
            if (file.size > maxSize) {
                fotoError.textContent = "A imagem deve ter no máximo 2MB.";
                showError(input, fotoError);
                return;
            }
            
            hideError(input, fotoError);
            
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile-picture').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    // Máscara para o telefone
    const telefoneInput = document.getElementById('telefone');
    telefoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 10) value = value.slice(0, 10);
        
        if (value.length > 6) {
            value = value.replace(/^(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
        } else if (value.length > 2) {
            value = value.replace(/^(\d{2})(\d{0,4})/, '($1) $2');
        } else if (value.length > 0) {
            value = value.replace(/^(\d{0,2})/, '($1');
        }
        
        e.target.value = value;
        validateTelefone();
    });

    // Validações
    const form = document.getElementById('editProfileForm');
    const name = document.getElementById('name');
    const email = document.getElementById('email');
    const telefone = document.getElementById('telefone');
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');

    // Exibir mensagens de erro
    function showError(input, errorDiv) {
        errorDiv.style.display = 'block';
        input.classList.add('is-invalid');
    }

    // Ocultar mensagens de erro
    function hideError(input, errorDiv) {
        errorDiv.style.display = 'none';
        input.classList.remove('is-invalid');
    }

    // Validar nome
    function validateName() {
        const nameError = document.getElementById('name-error');
        if (name.value.length < 3) {
            showError(name, nameError);
            return false;
        } else {
            hideError(name, nameError);
            return true;
        }
    }

    // Validar email
    function validateEmail() {
        const emailError = document.getElementById('email-error');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value)) {
            showError(email, emailError);
            return false;
        } else {
            hideError(email, emailError);
            return true;
        }
    }

    // Validar telefone
    function validateTelefone() {
        const telefoneError = document.getElementById('telefone-error');
        if (telefone.value.length !== 14) {
            showError(telefone, telefoneError);
            return false;
        } else {
            hideError(telefone, telefoneError);
            return true;
        }
    }

    // Validar senha
    function validatePassword() {
        const passwordError = document.getElementById('password-error');
        // Se o campo estiver vazio, consideramos válido (senha não será alterada)
        if (password.value === '') {
            hideError(password, passwordError);
            return true;
        }
        if (password.value.length < 5) {
            showError(password, passwordError);
            return false;
        } else {
            hideError(password, passwordError);
            return true;
        }
    }

    // Validar confirmação de senha
    function validatePasswordConfirmation() {
        const passwordConfirmationError = document.getElementById('password-confirmation-error');
        // Se ambos estiverem vazios, é válido
        if (password.value === '' && passwordConfirmation.value === '') {
            hideError(passwordConfirmation, passwordConfirmationError);
            return true;
        }
        if (password.value !== passwordConfirmation.value) {
            showError(passwordConfirmation, passwordConfirmationError);
            return false;
        } else {
            hideError(passwordConfirmation, passwordConfirmationError);
            return true;
        }
    }

    // Adicionar eventos de validação a cada campo
    name.addEventListener('input', validateName);
    email.addEventListener('input', validateEmail);
    telefone.addEventListener('input', validateTelefone);
    password.addEventListener('input', validatePassword);
    passwordConfirmation.addEventListener('input', validatePasswordConfirmation);
    password.addEventListener('input', validatePasswordConfirmation);

    // Validar formulário antes de enviar
    form.addEventListener('submit', function(event) {
        let isValid = true;
        
        if (!validateName()) isValid = false;
        if (!validateEmail()) isValid = false;
        if (!validateTelefone()) isValid = false;
        if (!validatePassword()) isValid = false;
        if (!validatePasswordConfirmation()) isValid = false;
        
        if (!isValid) {
            event.preventDefault();
        }
    });
</script>
@endpush
@endsection