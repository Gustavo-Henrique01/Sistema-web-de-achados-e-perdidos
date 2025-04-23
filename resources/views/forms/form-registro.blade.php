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

            <form action="{{ route('criar-usuario') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="text-center mb-4">
                    <img id="foto-preview" src="{{ asset('images/default-avatar.png') }}" class="logo-preview">
                    <div class="logo-upload">
                        <input type="file" id="foto" name="foto" accept="image/*" onchange="previewFoto(this)">
                        <label for="foto">
                            <i class="fas fa-camera me-2"></i>Upload Foto
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="nome" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" class="form-control @error('telefone') is-invalid @enderror" 
                               id="telefone" name="telefone" value="{{ old('telefone') }}" required>
                        @error('telefone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text" class="form-control @error('cpf') is-invalid @enderror" 
                               id="cpf" name="cpf" value="{{ old('cpf') }}" required>
                        @error('cpf')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control @error('senha') is-invalid @enderror" 
                               id="senha" name="senha" required>
                        @error('senha')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="senha_confirmation" class="form-label">Confirmar Senha</label>
                        <input type="password" class="form-control" 
                               id="senha_confirmation" name="senha_confirmation" required>
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
        function previewFoto(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('foto-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
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
            });

            // Máscara para telefone
            const telefoneInput = document.getElementById('telefone');
            telefoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 11) value = value.slice(0, 11);
                
                if (value.length > 10) {
                    value = value.replace(/^(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                } else if (value.length > 6) {
                    value = value.replace(/^(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                } else if (value.length > 2) {
                    value = value.replace(/^(\d{2})(\d{4})/, '($1) $2');
                } else if (value.length > 0) {
                    value = value.replace(/^(\d{2})/, '($1)');
                }
                
                e.target.value = value;
            });
        });
    </script>
</body>
</html>