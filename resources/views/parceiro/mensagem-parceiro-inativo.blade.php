@extends('layouts.message')

@section('header', 'Parceiro Inativo')
@section('subtitle', 'Sistema de Achados e Perdidos')

@section('content')
<div class="text-center mb-4">
    <div class="message-icon">
        <i class="fas fa-ban text-danger"></i>
    </div>
    <h3 class="mb-4">Acesso Temporariamente Suspenso</h3>
</div>

<div class="message-info">
    <p class="mb-0">Olá <strong>{{ auth()->user()->name }}</strong>,</p>
    <p>Seu estabelecimento <strong>{{ auth()->user()->parceiro->nome_estabelecimento }}</strong> está temporariamente inativo.</p>
    
    <div class="alert alert-warning mt-4">
        <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Motivo da Inativação:</h5>
        <p class="mb-0">{{ auth()->user()->parceiro->motivo_inativacao ?? 'Não especificado' }}</p>
    </div>

    <div class="mt-4">
        <p>Para contestar esta decisão ou obter mais informações, entre em contato através do email:</p>
        <p><a href="mailto:acheaqui.cg.ms@gmail.com" class="btn btn-primary">
            <i class="fas fa-envelope me-2"></i>acheaqui.cg.ms@gmail.com
        </a></p>
    </div>
</div>

<div class="message-actions mt-4">
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline-secondary">
            <i class="fas fa-sign-out-alt me-2"></i> Sair
        </button>
    </form>
</div>
@endsection