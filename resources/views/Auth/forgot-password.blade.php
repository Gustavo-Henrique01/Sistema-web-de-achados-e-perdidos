<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueceu a Senha - Achados e Perdidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .forgot-card {
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
        .back-to-login {
            color: #0d6efd;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        .back-to-login:hover {
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
        <div class="forgot-card p-4" style="width: 100%; max-width: 400px;">
            <a href="{{ route('paginaInicial') }}" class="back-home">
                <i class="bi bi-arrow-left me-2"></i>Voltar para a página inicial
            </a>

            <div class="text-center mb-4">
                <h3 class="fw-bold text-primary">Esqueceu sua senha?</h3>
                <p class="text-muted">Não se preocupe! Digite seu e-mail e enviaremos instruções para redefinir sua senha.</p>
            </div>

            @if(session('status'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
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

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-envelope-paper me-2"></i>Enviar Link de Redefinição
                    </button>
                </div>
            </form>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="back-to-login">
                    <i class="bi bi-arrow-left me-1"></i>Voltar para o login
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 