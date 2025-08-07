@extends('admin.dashboard')

@section('content')
<div class="container-fluid mt-4">
    <!-- Cabeçalho da página -->
    <div class="bg-white shadow-sm rounded-3 p-4 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2">
            <div class="mb-3 mb-md-0">
                <h2 class="fw-bold mb-0"><i class="fas fa-store me-2 text-primary"></i>Detalhes do Parceiro</h2>
                <p class="text-muted mb-0">{{ $parceiro->nome_estabelecimento }} - {{ $parceiro->cnpj }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary rounded-pill">
                    <i class="fas fa-arrow-left me-1"></i> Voltar ao Mapa
                </a>
                <a href="{{ route('admin.parceiros.itens', $parceiro) }}" class="btn btn-primary rounded-pill">
                    <i class="fas fa-box me-1"></i> Ver Itens
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Informações do Estabelecimento -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="fw-bold mb-0"><i class="fas fa-building me-2 text-primary"></i>Informações do Estabelecimento</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column align-items-center mb-4">
                        @if($parceiro->logo)
                            <div class="rounded-circle overflow-hidden mb-3" style="width: 120px; height: 120px; background-color: #f8f9fa;">
                                <img src="{{ asset('storage/' . $parceiro->logo) }}" 
                                    alt="{{ $parceiro->nome_estabelecimento }}" 
                                    class="w-100 h-100 object-fit-cover">
                            </div>
                        @else
                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 mb-3" style="width: 120px; height: 120px;">
                                <i class="fas fa-store fa-3x text-primary"></i>
                            </div>
                        @endif
                        <h4 class="fw-bold mb-1">{{ $parceiro->nome_estabelecimento }}</h4>
                        <p class="text-muted">CNPJ: {{ $parceiro->cnpj }}</p>
                    </div>

                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 py-3 d-flex border-top">
                            <div class="me-3"><i class="fas fa-tag fa-fw text-primary"></i></div>
                            <div class="fw-medium me-2">Tipo:</div>
                            <div class="ms-auto">
                                @if($parceiro->tipo_parceiro == 'ponto_coleta')
                                    <span class="badge rounded-pill bg-info bg-opacity-10 text-info px-3 py-2">
                                        <i class="fas fa-map-marker-alt me-1"></i> Ponto de Coleta
                                    </span>
                                @elseif($parceiro->tipo_parceiro == 'evento')
                                    <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3 py-2">
                                        <i class="fas fa-calendar-alt me-1"></i> Evento
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                        <i class="fas fa-random me-1"></i> Ambos
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="list-group-item px-0 py-3 d-flex border-top">
                            <div class="me-3"><i class="fas fa-align-left fa-fw text-primary"></i></div>
                            <div class="fw-medium me-2">Descrição:</div>
                            <div class="ms-auto text-end">{{ $parceiro->descricao ?? 'Não informada' }}</div>
                        </div>

                        <div class="list-group-item px-0 py-3 d-flex border-top">
                            <div class="me-3"><i class="fas fa-phone fa-fw text-primary"></i></div>
                            <div class="fw-medium me-2">Telefone:</div>
                            <div class="ms-auto text-end">{{ $parceiro->telefone_comercial ?? 'Não informado' }}</div>
                        </div>

                        <div class="list-group-item px-0 py-3 d-flex border-top">
                            <div class="me-3"><i class="fas fa-calendar-alt fa-fw text-primary"></i></div>
                            <div class="fw-medium me-2">Horário:</div>
                            <div class="ms-auto text-end">
                                @if($parceiro->horario_funcionamento)
                                    {{ $parceiro->horario_funcionamento }}
                                @else
                                    <span class="text-muted">Não informado</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informações do Responsável e Status -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="fw-bold mb-0"><i class="fas fa-user me-2 text-primary"></i>Informações do Responsável</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column align-items-center mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 mb-3" style="width: 100px; height: 100px;">
                            <i class="fas fa-user fa-3x text-primary"></i>
                        </div>
                        <h5 class="fw-bold mb-1">{{ $parceiro->usuario->name ?? 'Não informado' }}</h5>
                        <p class="text-muted">Responsável pelo estabelecimento</p>
                    </div>
                    
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 py-3 d-flex border-top">
                            <div class="me-3"><i class="fas fa-envelope fa-fw text-primary"></i></div>
                            <div class="fw-medium me-2">Email:</div>
                            <div class="ms-auto text-end">{{ $parceiro->usuario->email ?? 'Não informado' }}</div>
                        </div>

                        <div class="list-group-item px-0 py-3 d-flex border-top">
                            <div class="me-3"><i class="fas fa-phone fa-fw text-primary"></i></div>
                            <div class="fw-medium me-2">Telefone:</div>
                            <div class="ms-auto text-end">{{ $parceiro->usuario->telefone ?? 'Não informado' }}</div>
                        </div>

                        <div class="list-group-item px-0 py-3 d-flex border-top">
                            <div class="me-3"><i class="fas fa-id-card fa-fw text-primary"></i></div>
                            <div class="fw-medium me-2">CPF:</div>
                            <div class="ms-auto text-end">{{ $parceiro->usuario->cpf ?? 'Não informado' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Localização -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="fw-bold mb-0"><i class="fas fa-map-marker-alt me-2 text-primary"></i>Localização</h5>
                </div>
                <div class="card-body p-4">
                    @if($parceiro->localizacao)
                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0 py-3 d-flex border-top">
                                <div class="me-3"><i class="fas fa-map fa-fw text-primary"></i></div>
                                <div class="fw-medium me-2">Endereço:</div>
                                <div class="ms-auto text-end">
                                    {{ $parceiro->localizacao->logradouro ?? '' }}
                                    {{ $parceiro->localizacao->numero ? ', '.$parceiro->localizacao->numero : '' }}
                                </div>
                            </div>

                            <div class="list-group-item px-0 py-3 d-flex border-top">
                                <div class="me-3"><i class="fas fa-map-signs fa-fw text-primary"></i></div>
                                <div class="fw-medium me-2">Bairro:</div>
                                <div class="ms-auto text-end">{{ $parceiro->localizacao->bairro ?? 'Não informado' }}</div>
                            </div>

                            <div class="list-group-item px-0 py-3 d-flex border-top">
                                <div class="me-3"><i class="fas fa-city fa-fw text-primary"></i></div>
                                <div class="fw-medium me-2">Cidade/UF:</div>
                                <div class="ms-auto text-end">
                                    {{ $parceiro->localizacao->cidade ?? 'Não informada' }}
                                    {{ $parceiro->localizacao->estado ? '/'.$parceiro->localizacao->estado : '' }}
                                </div>
                            </div>

                            <div class="list-group-item px-0 py-3 d-flex border-top">
                                <div class="me-3"><i class="fas fa-mailbox fa-fw text-primary"></i></div>
                                <div class="fw-medium me-2">CEP:</div>
                                <div class="ms-auto text-end">{{ $parceiro->localizacao->cep ?? 'Não informado' }}</div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Nenhuma informação de localização cadastrada.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
