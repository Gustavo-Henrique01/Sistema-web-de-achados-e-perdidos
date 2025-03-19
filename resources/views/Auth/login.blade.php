@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0">
            <div class="card-header text-center py-3">
                <h4 class="mb-0">Bem-vindo de volta</h4>
            </div>
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <i class="fas fa-user-circle fa-3x text-primary"></i>
                    <p class="text-muted mt-2">Entre com suas credenciais para acessar</p>
                </div>

                <!-- Exibe mensagens de erro/sucesso -->
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <!-- Campo para e-mail -->
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2"></i>E-mail
                        </label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" required autofocus placeholder="Seu e-mail">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Campo para senha -->
                    <div class="mb-3">
                        <label for="senha" class="form-label">
                            <i class="fas fa-lock me-2"></i>Senha
                        </label>
                        <input type="password" id="senha" name="senha" 
                               class="form-control @error('senha') is-invalid @enderror" 
                               required placeholder="Sua senha">
                        @error('senha')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Lembrar-me -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Lembrar-me</label>
                    </div>

                    <!-- Botão de Login -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary py-2">
                            <i class="fas fa-sign-in-alt me-2"></i>Entrar
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer bg-light text-center py-3">
                <p class="mb-0">Não possui conta? <a href="{{ route('registrar') }}" class="text-decoration-none">Crie uma conta</a></p>
            </div>
        </div>
    </div>
</div>
@endsection