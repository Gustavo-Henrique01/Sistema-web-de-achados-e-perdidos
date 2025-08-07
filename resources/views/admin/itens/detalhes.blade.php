<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Item #{{ $item->id }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 0;
            margin: 0;
        }
        .modal-view {
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="modal-view">
    <div class="container-fluid">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
            <div class="mb-3 mb-md-0">
                <h2 class="fw-bold mb-0"><i class="fas fa-box-open me-2 text-primary"></i>Detalhes do Item</h2>
                <p class="text-muted mb-0">Informações completas do item #{{ $item->id }}</p>
            </div>
            <div>
                <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Fechar
                </button>
            </div>
        </div>
        
        @include('admin.partials.detalhes-item')
    </div>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
