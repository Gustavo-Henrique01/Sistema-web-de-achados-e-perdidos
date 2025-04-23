<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Achados e Perdidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .login-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
        }
        .form-control {
            border-radius: 5px;
            padding: 10px;
            border: 1px solid #ced4da;
        }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .btn-primary {
            background: #0d6efd;
            border: none;
            padding: 10px;
            border-radius: 5px;
            font-weight: 500;
        }
        .btn-primary:hover {
            background: #0b5ed7;
        }
        .btn-outline-primary {
            color: #0d6efd;
            border-color: #0d6efd;
        }
        .btn-outline-primary:hover {
            background: #0d6efd;
            color: white;
        }
        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .forgot-password {
            color: #0d6efd;
            text-decoration: none;
        }
        .forgot-password:hover {
            color: #0b5ed7;
            text-decoration: underline;
        }
        .back-home {
            color: #0d6efd;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        .back-home:hover {
            color: #0b5ed7;
            text-decoration: underline;
        }
        .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="login-card p-4" style="width: 100%; max-width: 400px;">
            <a href="{{ route('paginaInicial') }}" class="back-home">
                <i class="bi bi-arrow-left me-2"></i>Voltar para a página inicial
            </a>

            <div class="text-center mb-4">
                <h3 class="fw-bold text-primary">Bem-vindo de volta!</h3>
                <p class="text-muted">Faça login para continuar</p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" placeholder="seu@email.com" required autofocus>
                    </div>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" id="senha" name="senha" class="form-control @error('senha') is-invalid @enderror" 
                               placeholder="Digite sua senha" required>
                    </div>
                    @error('senha')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Lembrar-me</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="forgot-password">Esqueceu a senha?</a>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Entrar
                    </button>
                </div>
            </form>

            <div class="text-center mt-4">
                <p class="mb-0">Não possui conta?</p>
                <div class="d-flex justify-content-center gap-2 mt-2">
                    <a href="{{ route('registrar') }}" class="btn btn-outline-primary btn-sm">Criar conta como Usuário</a>
                    <a href="{{ route('parceiro.create') }}" class="btn btn-outline-primary btn-sm">Cadastrar como Parceiro</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>