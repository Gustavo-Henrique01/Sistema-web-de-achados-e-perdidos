@extends('layouts.parceiro')

@section('content')
<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-12">
            <div class="position-relative">
                <!-- Mapa -->
                <div id="map" style="height: 80vh; width: 100%;"></div>
                
                <!-- Painel de Controles -->
                <div class="position-absolute top-0 end-0 m-3 d-none d-md-block">
                    <div class="card shadow-lg border-0" style="width: 280px;">
                        <div class="card-header bg-primary text-white py-2 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 d-flex align-items-center">
                                <i class="fas fa-sliders-h me-2"></i>Filtros
                            </h6>
                            <button class="btn btn-sm btn-light toggle-panel" data-target="filter-panel">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <div id="filter-panel" class="card-body p-3">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Tipo</label>
                                <div class="btn-group w-100 shadow-sm" role="group">
                                    <input type="radio" class="btn-check" name="tipo" id="tipo_todos" value="" checked>
                                    <label class="btn btn-outline-secondary btn-sm" for="tipo_todos">
                                        <i class="fas fa-layer-group me-1"></i>Todos
                                    </label>
                                    
                                    <input type="radio" class="btn-check" name="tipo" id="tipo_achados" value="achado">
                                    <label class="btn btn-outline-success btn-sm" for="tipo_achados">
                                        <i class="fas fa-hand-holding me-1"></i>Achados
                                    </label>
                                    
                                    <input type="radio" class="btn-check" name="tipo" id="tipo_perdidos" value="perdido">
                                    <label class="btn btn-outline-warning btn-sm" for="tipo_perdidos">
                                        <i class="fas fa-search me-1"></i>Perdidos
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Status</label>
                                <div class="btn-group w-100 shadow-sm" role="group">
                                    <input type="radio" class="btn-check" name="status" id="status_todos" value="" checked>
                                    <label class="btn btn-outline-secondary btn-sm" for="status_todos">
                                        <i class="fas fa-bars me-1"></i>Todos
                                    </label>
                                    
                                    <input type="radio" class="btn-check" name="status" id="status_aprovado" value="aprovado">
                                    <label class="btn btn-outline-success btn-sm" for="status_aprovado">
                                        <i class="fas fa-check-circle me-1"></i>Aprovados
                                    </label>
                                    
                                    <input type="radio" class="btn-check" name="status" id="status_estabelecimento" value="em_estabelecimento">
                                    <label class="btn btn-outline-info btn-sm" for="status_estabelecimento">
                                        <i class="fas fa-store me-1"></i>Estabelecimentos Parceiros
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Categoria</label>
                                <div class="input-group input-group-sm shadow-sm">
                                    <span class="input-group-text bg-white"><i class="fas fa-tag text-muted"></i></span>
                                    <select class="form-select" id="categoria">
                                        <option value="">Todas as categorias</option>
                                        @foreach($categorias as $categoria)
                                            <option value="{{ $categoria->id }}">{{ $categoria->nome_categoria }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Data</label>
                                <div class="input-group input-group-sm shadow-sm">
                                    <span class="input-group-text bg-white"><i class="fas fa-calendar text-muted"></i></span>
                                    <input type="date" class="form-control" id="data">
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary btn-sm shadow-sm" onclick="aplicarFiltros()">
                                    <i class="fas fa-filter me-1"></i>Aplicar Filtros
                                </button>
                                
                                <button class="btn btn-outline-secondary btn-sm shadow-sm" onclick="resetarFiltros()">
                                    <i class="fas fa-undo me-1"></i>Limpar Filtros
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Legenda -->
                <div class="position-absolute top-0 start-0 m-3 d-none d-md-block">
                    <div class="card shadow-lg border-0" style="width: 180px;">
                        <div class="card-header bg-primary text-white py-2 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 d-flex align-items-center">
                                <i class="fas fa-info-circle me-2"></i>Legenda
                            </h6>
                            <button class="btn btn-sm btn-light toggle-panel" data-target="legend-panel">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <div id="legend-panel" class="card-body p-2">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-map-marker-alt text-success me-2"></i>
                                    <small class="text-muted">Itens Achados</small>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-map-marker-alt text-warning me-2"></i>
                                    <small class="text-muted">Itens Perdidos</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-map-marker-alt text-info me-2"></i>
                                    <small class="text-muted">Estabelecimentos Parceiros</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Painel de Controles Mobile -->
                <div class="d-block d-md-none position-absolute top-0 end-0 m-2">
                    <button id="filter-toggle" class="btn btn-primary btn-sm rounded-circle shadow" 
                            style="width: 40px; height: 40px;" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#mobile-filter-panel">
                        <i class="fas fa-filter"></i>
                    </button>
                    
                    <div id="mobile-filter-panel" class="collapse position-absolute top-0 end-0 mt-5 z-index-1000">
                        <div class="card shadow-lg" style="width: 280px;">
                            <div class="card-header bg-primary text-white py-2 d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="fas fa-sliders-h me-2"></i>Filtros
                                </h6>
                                <button class="btn btn-sm btn-light" data-bs-toggle="collapse" data-bs-target="#mobile-filter-panel">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="card-body p-3">
                                <!-- Conteúdo dos filtros mobile -->
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Tipo</label>
                                    <div class="btn-group w-100 shadow-sm" role="group">
                                        <input type="radio" class="btn-check" name="tipo_mobile" id="tipo_todos_mobile" value="" checked>
                                        <label class="btn btn-outline-secondary btn-sm" for="tipo_todos_mobile">
                                            <i class="fas fa-layer-group me-1"></i>Todos
                                        </label>
                                        
                                        <input type="radio" class="btn-check" name="tipo_mobile" id="tipo_achados_mobile" value="achado">
                                        <label class="btn btn-outline-success btn-sm" for="tipo_achados_mobile">
                                            <i class="fas fa-hand-holding me-1"></i>Achados
                                        </label>
                                        
                                        <input type="radio" class="btn-check" name="tipo_mobile" id="tipo_perdidos_mobile" value="perdido">
                                        <label class="btn btn-outline-warning btn-sm" for="tipo_perdidos_mobile">
                                            <i class="fas fa-search me-1"></i>Perdidos
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Status</label>
                                    <div class="btn-group w-100 shadow-sm" role="group">
                                        <input type="radio" class="btn-check" name="status_mobile" id="status_todos_mobile" value="" checked>
                                        <label class="btn btn-outline-secondary btn-sm" for="status_todos_mobile">
                                            <i class="fas fa-bars me-1"></i>Todos
                                        </label>
                                        
                                        <input type="radio" class="btn-check" name="status_mobile" id="status_aprovado_mobile" value="aprovado">
                                        <label class="btn btn-outline-success btn-sm" for="status_aprovado_mobile">
                                            <i class="fas fa-check-circle me-1"></i>Aprovados
                                        </label>
                                        
                                        <input type="radio" class="btn-check" name="status_mobile" id="status_estabelecimento_mobile" value="em_estabelecimento">
                                        <label class="btn btn-outline-info btn-sm" for="status_estabelecimento_mobile">
                                            <i class="fas fa-store me-1"></i>Estabelecimentos Parceiros
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Categoria</label>
                                    <div class="input-group input-group-sm shadow-sm">
                                        <span class="input-group-text bg-white"><i class="fas fa-tag text-muted"></i></span>
                                        <select class="form-select" id="categoria_mobile">
                                            <option value="">Todas as categorias</option>
                                            @foreach($categorias as $categoria)
                                                <option value="{{ $categoria->id }}">{{ $categoria->nome_categoria }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Data</label>
                                    <div class="input-group input-group-sm shadow-sm">
                                        <span class="input-group-text bg-white"><i class="fas fa-calendar text-muted"></i></span>
                                        <input type="date" class="form-control" id="data_mobile">
                                    </div>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary btn-sm shadow-sm" onclick="aplicarFiltros(true)">
                                        <i class="fas fa-filter me-1"></i>Aplicar Filtros
                                    </button>
                                    
                                    <button class="btn btn-outline-secondary btn-sm shadow-sm" onclick="resetarFiltros(true)">
                                        <i class="fas fa-undo me-1"></i>Limpar Filtros
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
