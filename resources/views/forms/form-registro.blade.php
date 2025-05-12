<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            margin: 50px auto;
            max-width: 600px;
        }
        .form-title {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 600;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px;
            border: 2px solid #e9ecef;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        .btn-primary {
            background: #3498db;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }
        .form-label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        .alert {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .logo-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e9ecef;
            margin: 0 auto 20px;
            display: block;
        }
        .logo-upload {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo-upload input[type="file"] {
            display: none;
        }
        .logo-upload label {
            cursor: pointer;
            padding: 10px 20px;
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            transition: all 0.3s;
            display: inline-block;
        }
        .logo-upload label:hover {
            background: #e9ecef;
        }
        .feedback-error {
            color: #dc3545;
            font-size: 80%;
            margin-top: 0.25rem;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <a href="{{ route('form.login') }}" class="text-decoration-none mb-3 d-inline-block">
                <i class="fas fa-arrow-left me-2"></i>Voltar para Login
            </a>

            <h2 class="form-title">Cadastro de Usuário</h2>
            <p class="text-center text-muted mb-4">Preencha seus dados para se registrar</p>
            
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            <form action="{{ route('criar-usuario') }}" method="POST" enctype="multipart/form-data" id="cadastroForm" novalidate>
                @csrf

                <div class="text-center mb-4">
                    <img id="foto-preview" src="{{ asset('images/default-avatar.png') }}" class="logo-preview">
                    <div class="logo-upload">
                        <input type="file" id="foto" name="foto" accept="image/jpeg, image/png, image/jpg, image/gif" onchange="previewFoto(this)">
                        <label for="foto">
                            <i class="fas fa-camera me-2"></i>Upload Foto
                        </label>
                    </div>
                    <small class="text-muted d-block">Formatos aceitos: JPG, JPEG, PNG, GIF (máx. 2MB)</small>
                    <div class="feedback-error mt-2" id="foto-error">Selecione uma imagem válida nos formatos: JPG, JPEG, PNG ou GIF (máx. 2MB).</div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="nome" name="name" value="{{ old('name') }}" required
                               minlength="3">
                        <div class="feedback-error" id="nome-error">O nome deve ter no mínimo 3 caracteres.</div>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        <div class="feedback-error" id="email-error">Digite um endereço de e-mail válido.</div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" class="form-control @error('telefone') is-invalid @enderror" 
                               id="telefone" name="telefone" value="{{ old('telefone') }}" required
                               minlength="14" maxlength="15">
                        <div class="feedback-error" id="telefone-error">O telefone deve estar no formato (99) 99999-9999.</div>
                        @error('telefone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text" class="form-control @error('cpf') is-invalid @enderror" 
                               id="cpf" name="cpf" value="{{ old('cpf') }}" required
                               minlength="14" maxlength="14">
                        <div class="feedback-error" id="cpf-error">O CPF deve estar no formato 999.999.999-99.</div>
                        @error('cpf')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control @error('senha') is-invalid @enderror" 
                               id="senha" name="senha" required minlength="5">
                        <div class="feedback-error" id="senha-error">A senha deve ter no mínimo 5 caracteres.</div>
                        @error('senha')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="senha_confirmation" class="form-label">Confirmar Senha</label>
                        <input type="password" class="form-control" 
                               id="senha_confirmation" name="senha_confirmation" required minlength="5">
                        <div class="feedback-error" id="senha-confirmation-error">As senhas não coincidem.</div>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Cadastrar
                    </button>
                </div>

                <div class="text-center mt-3">
                    <p class="mb-0">Já possui uma conta? 
                        <a href="{{ route('form.login') }}" class="text-decoration-none">Faça login</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Funções de utilidade no escopo global
        function showError(input, errorDiv) {
            errorDiv.style.display = 'block';
            input.classList.add('is-invalid');
        }

        function hideError(input, errorDiv) {
            errorDiv.style.display = 'none';
            input.classList.remove('is-invalid');
        }
        
        function previewFoto(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fotoError = document.getElementById('foto-error');
                const preview = document.getElementById('foto-preview');
                
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
                
                // Criar um objeto URL para a prévia imediata
                preview.src = URL.createObjectURL(file);
                
                // Também usar FileReader como backup
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }

        // Máscaras de formatação
        document.addEventListener('DOMContentLoaded', function() {
            // Máscara para CPF
            const cpfInput = document.getElementById('cpf');
            cpfInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 11) value = value.slice(0, 11);
                
                if (value.length > 9) {
                    value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                } else if (value.length > 6) {
                    value = value.replace(/^(\d{3})(\d{3})(\d{3})/, '$1.$2.$3');
                } else if (value.length > 3) {
                    value = value.replace(/^(\d{3})(\d{3})/, '$1.$2');
                }
                
                e.target.value = value;
                validateCPF();
            });

            // Máscara para telefone
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
            const form = document.getElementById('cadastroForm');
            const nome = document.getElementById('nome');
            const email = document.getElementById('email');
            const cpf = document.getElementById('cpf');
            const telefone = document.getElementById('telefone');
            const senha = document.getElementById('senha');
            const senhaConfirmation = document.getElementById('senha_confirmation');

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
            function validateNome() {
                const nomeError = document.getElementById('nome-error');
                if (nome.value.length < 3) {
                    showError(nome, nomeError);
                    return false;
                } else {
                    hideError(nome, nomeError);
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

            // Validar CPF
            function validateCPF() {
                const cpfError = document.getElementById('cpf-error');
                if (cpf.value.length !== 14) {
                    showError(cpf, cpfError);
                    return false;
                } else {
                    hideError(cpf, cpfError);
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
            function validateSenha() {
                const senhaError = document.getElementById('senha-error');
                if (senha.value.length < 5) {
                    showError(senha, senhaError);
                    return false;
                } else {
                    hideError(senha, senhaError);
                    return true;
                }
            }

            // Validar confirmação de senha
            function validateSenhaConfirmation() {
                const senhaConfirmationError = document.getElementById('senha-confirmation-error');
                if (senha.value !== senhaConfirmation.value) {
                    showError(senhaConfirmation, senhaConfirmationError);
                    return false;
                } else {
                    hideError(senhaConfirmation, senhaConfirmationError);
                    return true;
                }
            }

            // Adicionar eventos de validação a cada campo
            nome.addEventListener('input', validateNome);
            email.addEventListener('input', validateEmail);
            cpf.addEventListener('input', validateCPF);
            telefone.addEventListener('input', validateTelefone);
            senha.addEventListener('input', validateSenha);
            senhaConfirmation.addEventListener('input', validateSenhaConfirmation);
            senha.addEventListener('input', validateSenhaConfirmation);

            // Validar formulário antes de enviar
            form.addEventListener('submit', function(event) {
                let isValid = true;
                
                if (!validateNome()) isValid = false;
                if (!validateEmail()) isValid = false;
                if (!validateCPF()) isValid = false;
                if (!validateTelefone()) isValid = false;
                if (!validateSenha()) isValid = false;
                if (!validateSenhaConfirmation()) isValid = false;
                
                if (!isValid) {
                    event.preventDefault();
                }
            });
        });
    </script>
</body>
</html>