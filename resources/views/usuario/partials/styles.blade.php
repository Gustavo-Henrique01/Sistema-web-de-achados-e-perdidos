<!-- Estilos para o perfil do usuário -->
<style>
    /* Variáveis de cores */
    :root {
        --primary-color: #0d6efd;
        --primary-light: #e6f0ff;
        --success-color: #198754;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
        --light-gray: #f8f9fa;
        --medium-gray: #e9ecef;
        --dark-gray: #6c757d;
    }

    /* Estilos para o autocomplete */
    .ui-autocomplete {
        position: absolute;
        z-index: 9999 !important;
        max-height: 200px;
        overflow-y: auto;
        overflow-x: hidden;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        padding: 5px 0;
    }
    
    .ui-menu-item {
        padding: 5px 10px;
        cursor: pointer;
        list-style: none;
    }
    
    .ui-menu-item:hover,
    .ui-state-active {
        background-color: #f0f0f0;
    }
    
    .ui-helper-hidden-accessible {
        display: none;
    }
    
    /* Estilos para o perfil do usuário */
    .profile-card {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        margin-bottom: 30px;
    }
    
    .profile-header {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        padding: 20px;
        position: relative;
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 5px solid white;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        margin: 0 auto;
        overflow: hidden;
        position: relative;
        top: 60px;
        margin-bottom: 30px;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .profile-info {
        padding: 80px 20px 20px;
        background-color: white;
    }
    
    .profile-stats {
        background-color: #f8f9fc;
        padding: 15px;
        border-top: 1px solid #e3e6f0;
        font-size: 0.875rem;
    }
    
    .stat-item {
        padding: 8px 0;
        border-bottom: 1px dashed #e3e6f0;
        overflow: hidden;
    }
    
    .stat-item:last-child {
        border-bottom: none;
    }
    
    /* Garantir que os textos longos fiquem contidos */
    .text-truncate {
        max-width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }
    
    /* Melhorar a exibição em dispositivos móveis */
    @media (max-width: 576px) {
        .profile-stats .row .col-5,
        .profile-stats .row .col-7 {
            width: 100%;
            text-align: left !important;
        }
        
        .profile-stats .row {
            display: block;
            margin-bottom: 5px;
        }
    }
    
    /* Estilos para os itens cadastrados */
    .items-container {
        margin-bottom: 30px;
    }
    
    .items-header {
        background: linear-gradient(135deg, #36b9cc 0%, #1a8a98 100%);
        color: white;
        padding: 20px;
        border-radius: 15px 15px 0 0;
    }
    
    .items-body {
        background-color: white;
        border-radius: 0 0 15px 15px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        padding: 20px;
    }
    
    .item-card {
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.1);
        height: 100%;
    }
    
    .item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.2);
    }
    
    .item-gallery {
        position: relative;
    }
    
    .main-photo {
        height: 200px;
        background-color: #e9ecef;
        overflow: hidden;
        position: relative;
    }
    
    .main-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .photo-thumbnails {
        display: flex;
        padding: 5px;
        background-color: rgba(0,0,0,0.03);
        overflow-x: auto;
    }
    
    .photo-thumbnail {
        width: 50px;
        height: 50px;
        margin-right: 5px;
        border: 2px solid #fff;
        border-radius: 4px;
        cursor: pointer;
        overflow: hidden;
        flex-shrink: 0;
    }
    
    .photo-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }
    
    .photo-thumbnail:hover img {
        transform: scale(1.1);
    }
    
    .photo-thumbnail.active {
        border-color: #4e73df;
    }
    
    /* Ícones de navegação da galeria */
    .gallery-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255,255,255,0.7);
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        color: #333;
        transition: all 0.2s;
    }
    
    .gallery-nav:hover {
        background: rgba(255,255,255,0.9);
    }
    
    .gallery-prev {
        left: 10px;
    }
    
    .gallery-next {
        right: 10px;
    }
    
    .item-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: bold;
        text-transform: uppercase;
    }
    
    .badge-achado {
        background-color: #1cc88a;
        color: white;
    }
    
    .badge-perdido {
        background-color: #e74a3b;
        color: white;
    }
    
    .item-content {
        padding: 15px;
    }
    
    .item-title {
        font-weight: 600;
        margin-bottom: 10px;
        color: #4e73df;
    }
    
    .item-info {
        font-size: 0.875rem;
        color: #5a5c69;
        margin-bottom: 5px;
    }
    
    .item-footer {
        background-color: #f8f9fc;
        border-top: 1px solid #e3e6f0;
        padding: 10px 15px;
        display: flex;
        justify-content: space-between;
    }
    
    .pagination-container {
        margin-top: 20px;
    }
    
    .no-items {
        padding: 30px;
        text-align: center;
        color: #6c757d;
    }
    
    .no-items i {
        font-size: 3rem;
        margin-bottom: 10px;
        color: #ddd;
    }
    
    /* Estilos para o filtro de status */
    .status-filter {
        margin-bottom: 20px;
    }
    
    .filter-btn {
        border-radius: 20px;
        font-size: 0.85rem;
        margin-right: 5px;
        padding: 5px 15px;
        opacity: 0.7;
        transition: all 0.2s;
    }
    
    .filter-btn:hover, .filter-btn.active {
        opacity: 1;
        transform: translateY(-2px);
    }
    
    .filter-btn.all {
        background-color: #4e73df;
        border-color: #4e73df;
    }
    
    .filter-btn.approved {
        background-color: #1cc88a;
        border-color: #1cc88a;
    }
    
    .filter-btn.pending {
        background-color: #f6c23e;
        border-color: #f6c23e;
    }
    
    .filter-btn.rejected {
        background-color: #e74a3b;
        border-color: #e74a3b;
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
        .profile-avatar {
            width: 100px;
            height: 100px;
            top: 50px;
        }
        
        .profile-info {
            padding-top: 70px;
        }
        
        .main-photo {
            height: 150px;
        }
    }
    
    /* Estilos adicionais para garantir que o autocomplete seja visível */
    .ui-front {
        z-index: 10000 !important; /* Garante que o dropdown fique acima do modal */
    }
</style>
