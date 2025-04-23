@extends('layouts.message')

@section('header', 'Cadastro em Análise')
@section('subtitle', 'Sistema de Achados e Perdidos')

@section('content')
<div class="text-center mb-4">
    <div class="message-icon">
        @if($parceiro->status === 'reprovado')
            <i class="fas fa-times-circle"></i>
        @else
            <i class="fas fa-clock"></i>
        @endif
    </div>
    <h3 class="mb-4">
        @if($parceiro->status === 'reprovado')
            Cadastro Reprovado
        @else
            Aguardando Aprovação
        @endif
    </h3>
</div>

<div class="message-info">
    <p class="mb-0">Olá <strong>{{ auth()->user()->name }}</strong>,</p>
    @if($parceiro->status === 'reprovado')
        <p>Infelizmente seu cadastro <strong>{{ $parceiro->nome_estabelecimento }}</strong> não foi aprovado.</p>
        <p>Por favor, verifique o motivo da reprovação abaixo e entre em contato com nosso suporte caso tenha dúvidas.</p>
    @else
        <p>Agradecemos por se cadastrar como parceiro do nosso sistema de Achados e Perdidos!</p>
        <p>Seu cadastro <strong>{{ $parceiro->nome_estabelecimento }}</strong> está sendo analisado por nossa equipe e será processado em até 3 dias úteis.</p>
        <p class="mb-0">Você receberá uma notificação por e-mail assim que sua conta for aprovada. Por favor, verifique também sua caixa de spam.</p>
    @endif
</div>

<div class="card mt-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">Informações do Cadastro</h5>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4 fw-bold">Nome do Estabelecimento:</div>
            <div class="col-md-8">{{ $parceiro->nome_estabelecimento }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4 fw-bold">Tipo:</div>
            <div class="col-md-8">
                @if($parceiro->tipo_parceiro == 'ponto_coleta')
                    <span class="badge bg-info">Ponto de Coleta</span>
                @elseif($parceiro->tipo_parceiro == 'evento')
                    <span class="badge bg-primary">Evento</span>
                @else
                    <span class="badge bg-secondary">Ambos</span>
                @endif
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4 fw-bold">Data de Cadastro:</div>
            <div class="col-md-8">{{ $parceiro->created_at->format('d/m/Y H:i') }}</div>
        </div>
        <div class="row">
            <div class="col-md-4 fw-bold">Status:</div>
            <div class="col-md-8">
                @if($parceiro->status === 'reprovado')
                    <span class="badge bg-danger">Reprovado</span>
                @else
                    <span class="badge bg-warning">Em análise</span>
                @endif
            </div>
        </div>
        
        @if($parceiro->status === 'reprovado')
        <div class="row mt-3">
            <div class="col-md-4 fw-bold">Motivo da Reprovação:</div>
            <div class="col-md-8 text-danger">
                {{ $parceiro->motivo_reprovacao }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('footer')
<div class="message-actions">
    <p class="mb-3">Caso tenha alguma dúvida, entre em contato com nosso suporte pelo e-mail <a href="mailto:acheaqui.cg.ms@gmail.com">acheaqui.cg.ms@gmail.com</a></p>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline-secondary">
            <i class="fas fa-sign-out-alt me-2"></i> Sair
        </button>
    </form>
</div>
@endsection 