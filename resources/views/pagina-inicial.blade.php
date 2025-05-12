<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Achados e Perdidos - Campo Grande</title>
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    :root {
      --primary-color: #2563eb;
      --secondary-color: #f8fafc;
      --accent-color: #0ea5e9;
      --text-color: #334155;
      --success-color: #22c55e;
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      color: var(--text-color);
      overflow-x: hidden;
    }
    
    .navbar {
      background-color: rgba(255, 255, 255, 0.95) !important;
      backdrop-filter: blur(10px);
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      padding: 1rem 0;
    }

    @media (max-width: 991.98px) {
      .navbar {
        padding: 0.5rem 0;
      }
      
      .navbar-collapse {
        background: white;
        padding: 1rem;
        border-radius: 0.5rem;
        margin-top: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      }
      
      .nav-link {
        padding: 0.5rem 1rem;
      }
    }

    .navbar.scrolled {
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand {
      font-weight: 600;
      color: var(--primary-color) !important;
      font-size: 1.5rem;
    }

    @media (max-width: 767.98px) {
      .navbar-brand {
        font-size: 1.25rem;
      }
    }

    .nav-link {
      color: var(--text-color) !important;
      font-weight: 500;
      position: relative;
      transition: all 0.3s ease;
      padding: 0.5rem 1rem;
    }

    .nav-link::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      width: 0;
      height: 2px;
      background: var(--primary-color);
      transition: all 0.3s ease;
      transform: translateX(-50%);
    }

    .nav-link:hover::after {
      width: 100%;
    }

    .nav-link:hover {
      color: var(--primary-color) !important;
    }
    
    .hero-section {
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
      color: white;
      padding: 8rem 0 5rem;
      position: relative;
      overflow: hidden;
    }

    @media (max-width: 991.98px) {
      .hero-section {
        padding: 6rem 0 3rem;
      }
    }

    @media (max-width: 767.98px) {
      .hero-section {
        padding: 5rem 0 2rem;
      }
    }
    
    .hero-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('https://www.campogrande.ms.gov.br/wp-content/uploads/2022/05/0F0A2139-2-scaled.jpg') center/cover;
      opacity: 0.15;
      filter: grayscale(50%);
    }
    
    .search-box {
      background: rgba(255, 255, 255, 0.95);
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 767.98px) {
      .search-box {
        padding: 1.5rem;
      }
    }
    
    .feature-card {
      border-radius: 1rem;
      border: none;
      transition: all 0.3s ease;
      height: 100%;
      background: white;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      overflow: hidden;
      padding: 2rem;
    }

    @media (max-width: 767.98px) {
      .feature-card {
        padding: 1.5rem;
      }
    }
    
    .feature-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 20px rgba(0, 0, 0, 0.1);
    }
    
    .feature-icon {
      background: var(--primary-color);
      color: white;
      width: 70px;
      height: 70px;
      border-radius: 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      font-size: 1.75rem;
    }

    @media (max-width: 767.98px) {
      .feature-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
      }
    }
    
    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
      padding: 0.75rem 1.5rem;
      font-weight: 500;
      border-radius: 0.75rem;
      transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
      background-color: #1d4ed8;
      border-color: #1d4ed8;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
    }
    
    .stats-box {
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
      border-radius: 1rem;
      padding: 2.5rem;
      color: white;
      text-align: center;
      height: 100%;
      transition: all 0.3s ease;
    }

    @media (max-width: 767.98px) {
      .stats-box {
        padding: 2rem;
      }
    }
    
    .stats-box:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 20px rgba(0, 0, 0, 0.1);
    }
    
    .icon-circle {
      width: 80px;
      height: 80px;
      background-color: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto;
    }

    @media (max-width: 767.98px) {
      .icon-circle {
        width: 70px;
        height: 70px;
      }
    }
    
    .stats-number {
      font-size: 3rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    @media (max-width: 767.98px) {
      .stats-number {
        font-size: 2.5rem;
      }
    }
    
    .stats-label {
      font-size: 1.1rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      opacity: 0.9;
    }

    @media (max-width: 767.98px) {
      .stats-label {
        font-size: 1rem;
      }
    }

    .city-card {
      position: relative;
      border-radius: 1rem;
      overflow: hidden;
      height: 250px;
      margin-bottom: 1.5rem;
    }

    @media (max-width: 767.98px) {
      .city-card {
        height: 200px;
      }
    }

    .city-card img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .city-card:hover img {
      transform: scale(1.05);
    }

    .city-card-overlay {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      padding: 1.5rem;
      background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
      color: white;
    }

    @media (max-width: 767.98px) {
      .city-card-overlay {
        padding: 1rem;
      }
    }

    .city-card-overlay h5 {
      margin: 0;
      font-size: 1.25rem;
    }

    @media (max-width: 767.98px) {
      .city-card-overlay h5 {
        font-size: 1.1rem;
      }
    }

    .city-card-overlay p {
      margin: 0.25rem 0 0;
      opacity: 0.9;
    }

    .section-title {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 1rem;
    }

    @media (max-width: 767.98px) {
      .section-title {
        font-size: 2rem;
      }
    }

    .section-subtitle {
      font-size: 1.25rem;
      color: #64748b;
      margin-bottom: 3rem;
    }

    @media (max-width: 767.98px) {
      .section-subtitle {
        font-size: 1.1rem;
        margin-bottom: 2rem;
      }
    }

    .badge {
      padding: 0.5rem 1rem;
      font-weight: 500;
      border-radius: 2rem;
      margin: 0.25rem;
      transition: all 0.3s ease;
    }

    @media (max-width: 767.98px) {
      .badge {
        padding: 0.4rem 0.8rem;
        font-size: 0.9rem;
      }
    }

    .hero-image {
      height: 400px;
      object-fit: cover;
      border-radius: 1rem;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 991.98px) {
      .hero-image {
        height: 350px;
      }
    }

    @media (max-width: 767.98px) {
      .hero-image {
        height: 300px;
      }
    }

    .timeline-marker {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 1.25rem;
      flex-shrink: 0;
    }

    .timeline-item {
      position: relative;
    }

    .timeline-item:not(:last-child)::after {
      content: '';
      position: absolute;
      left: 20px;
      top: 40px;
      bottom: -20px;
      width: 2px;
      background-color: var(--primary-color);
      opacity: 0.2;
    }

    .partner-icon {
      width: 80px;
      height: 80px;
      background: var(--primary-color);
      color: white;
      border-radius: 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
    }

    .benefit-icon {
      width: 50px;
      height: 50px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.25rem;
      flex-shrink: 0;
    }

    @media (max-width: 767.98px) {
      .timeline-marker {
        width: 32px;
        height: 32px;
        font-size: 1rem;
      }

      .timeline-item:not(:last-child)::after {
        left: 16px;
      }

      .partner-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
      }

      .benefit-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
      }
    }

    .footer {
  background-color: #f8f9fa;
  padding: 2rem 0;
  margin-top: auto;
  border-top: 1px solid #e9ecef;
}

.footer h5 {
  font-size: 1.1rem;
  margin-bottom: 1rem;
  color: var(--text-color);
}

.footer-link {
  color: #6c757d;
  text-decoration: none;
  transition: color 0.3s ease;
}

.footer-link:hover {
  color: var(--primary-color);
}
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#">
        <i class="fas fa-search me-2"></i>
        Ache Aqui CG
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="#como-funciona">Como Funciona</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#parceiros">Seja Parceiro</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#estatisticas">Estatísticas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('parceiros.mapa') }}">Pontos de Coleta</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="{{ route('form.login') }}">Entrar</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-primary" href="{{ route('registrar') }}">Cadastrar</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="container">
      <div class="row align-items-center mt-auto">
        <div class="col-lg-7 mb-5 mb-lg-0">
          <h1 class="display-4 fw-bold mb-4">Encontre ou Devolva Itens Perdidos em Campo Grande</h1>
          <p class="lead mb-4">Conectamos pessoas que perderam seus pertences com aquelas que os encontraram. Uma iniciativa para tornar Campo Grande uma cidade mais solidária.</p>
          <div class="d-flex flex-wrap gap-3">
            <a href="{{ route('form.login') }}" class="btn btn-light btn-lg">
              <i class="fas fa-search me-2"></i>Procurar Item
            </a>
            <a href="{{ route('form.login') }}" class="btn btn-outline-light btn-lg">
              <i class="fas fa-hand-holding-heart me-2"></i>Reportar Achado
            </a>
          </div>
          <div class="mt-4 d-flex flex-wrap align-items-center">
            <span class="badge bg-light text-primary me-2 mb-2 p-2">Celulares</span>
            <span class="badge bg-light text-primary me-2 mb-2 p-2">Documentos</span>
            <span class="badge bg-light text-primary me-2 mb-2 p-2">Carteiras</span>
            <span class="badge bg-light text-primary me-2 mb-2 p-2">Chaves</span>
            <span class="text-light ms-2 mb-2">Categorias populares de itens</span>
          </div>
        </div>
        <div class="col-lg-5 d-none d-lg-block">
          <img src="{{ Storage::url('img-page\araras.webp') }}" 
               alt="Campo Grande" 
               class="img-fluid rounded-2" 
               style="object-fit: cover; height: 400px; width: 100%;">
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="py-5 bg-white">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="display-5 fw-bold">Por que usar nosso sistema?</h2>
        <p class="lead text-muted">Ajudamos a reunir pessoas e seus pertences em Campo Grande</p>
      </div>
      
      <div class="row g-4">
        <div class="col-md-4">
          <div class="feature-card p-4 text-center">
            <div class="feature-icon mb-4">
              <i class="fas fa-map-marker-alt"></i>
            </div>
            <h4 class="fw-bold mb-3">Mapa Interativo</h4>
            <p>Visualize todos os itens em um mapa da cidade, facilitando a localização de objetos perdidos ou encontrados.</p>
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="feature-card p-4 text-center">
            <div class="feature-icon mb-4">
              <i class="fas fa-tags"></i>
            </div>
            <h4 class="fw-bold mb-3">Categorias Organizadas</h4>
            <p>Busque itens por categorias específicas como documentos, eletrônicos, acessórios e muito mais.</p>
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="feature-card p-4 text-center">
            <div class="feature-icon mb-4">
              <i class="fas fa-store"></i>
            </div>
            <h4 class="fw-bold mb-3">Pontos de Coleta</h4>
            <p>Nossa rede de parceiros em Campo Grande facilita a entrega e retirada de itens perdidos.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Statistics Section -->
  <section class="py-5" id="estatisticas">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="display-5 fw-bold">Nosso Impacto em Campo Grande</h2>
        <p class="lead text-muted">Ajudamos a conectar pessoas e seus pertences perdidos</p>
      </div>
      
      <div class="row g-4">
        <div class="col-md-4">
          <div class="stats-box d-flex flex-column align-items-center">
            <div class="icon-circle mb-3">
              <i class="fas fa-handshake fa-2x"></i>
            </div>
            <div class="stats-number">250+</div>
            <div class="stats-label">Itens Devolvidos</div>
            <p class="mt-3 mb-0 text-center">Histórias de sucesso em nossa comunidade</p>
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="stats-box d-flex flex-column align-items-center">
            <div class="icon-circle mb-3">
              <i class="fas fa-users fa-2x"></i>
            </div>
            <div class="stats-number">500+</div>
            <div class="stats-label">Usuários Ativos</div>
            <p class="mt-3 mb-0 text-center">Campo-grandenses conectados</p>
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="stats-box d-flex flex-column align-items-center">
            <div class="icon-circle mb-3">
              <i class="fas fa-building fa-2x"></i>
            </div>
            <div class="stats-number">25+</div>
            <div class="stats-label">Pontos Parceiros</div>
            <p class="mt-3 mb-0 text-center">Locais seguros para devolução</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- How it Works Section -->
  <section class="py-5 bg-light" id="como-funciona">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="section-title">Como Funciona</h2>
        <p class="section-subtitle">Um processo simples e seguro para recuperar seus pertences</p>
      </div>
      
      <div class="row g-4">
        <div class="col-lg-6">
          <div class="card border-0 shadow-sm h-100 p-4">
            <div class="card-body">
              <h3 class="fw-bold mb-4 d-flex align-items-center">
                <i class="fas fa-search text-primary me-3"></i>
                Perdeu algo?
              </h3>
              
              <div class="timeline">
                <div class="timeline-item mb-4">
                  <div class="d-flex">
                    <div class="timeline-marker bg-primary text-white">1</div>
                    <div class="ms-4">
                      <h5 class="fw-bold">Faça seu cadastro</h5>
                      <p class="mb-0 text-muted">Crie uma conta gratuita para acessar todas as funcionalidades.</p>
                    </div>
                  </div>
                </div>
                
                <div class="timeline-item mb-4">
                  <div class="d-flex">
                    <div class="timeline-marker bg-primary text-white">2</div>
                    <div class="ms-4">
                      <h5 class="fw-bold">Registre o item perdido</h5>
                      <p class="mb-0 text-muted">Descreva o objeto, local e data aproximada da perda.</p>
                    </div>
                  </div>
                </div>
                
                <div class="timeline-item">
                  <div class="d-flex">
                    <div class="timeline-marker bg-primary text-white">3</div>
                    <div class="ms-4">
                      <h5 class="fw-bold">Busque por itens semelhantes</h5>
                      <p class="mb-0 text-muted">Encontre itens semelhante na lista de itens cadastrados.</p>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="text-center mt-4">
                <a href="{{ route('form.login') }}" class="btn btn-primary btn-lg">
                  <i class="fas fa-search me-2"></i>Reportar Item Perdido
                </a>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-lg-6">
          <div class="card border-0 shadow-sm h-100 p-4">
            <div class="card-body">
              <h3 class="fw-bold mb-4 d-flex align-items-center">
                <i class="fas fa-hand-holding-heart text-primary me-3"></i>
                Encontrou algo?
              </h3>
              
              <div class="timeline">
                <div class="timeline-item mb-4">
                  <div class="d-flex">
                    <div class="timeline-marker bg-primary text-white">1</div>
                    <div class="ms-4">
                      <h5 class="fw-bold">Cadastre-se</h5>
                      <p class="mb-0 text-muted">Crie sua conta para registrar o item encontrado.</p>
                    </div>
                  </div>
                </div>
                
                <div class="timeline-item mb-4">
                  <div class="d-flex">
                    <div class="timeline-marker bg-primary text-white">2</div>
                    <div class="ms-4">
                      <h5 class="fw-bold">Descreva o item</h5>
                      <p class="mb-0 text-muted">Adicione fotos e informações detalhadas do objeto.</p>
                    </div>
                  </div>
                </div>
                
                <div class="timeline-item">
                  <div class="d-flex">
                    <div class="timeline-marker bg-primary text-white">3</div>
                    <div class="ms-4">
                      <h5 class="fw-bold">Entregue com segurança</h5>
                      <p class="mb-0 text-muted">Use nossos pontos parceiros para uma devolução segura.</p>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="text-center mt-4">
                <a href="{{ route('form.login') }}" class="btn btn-primary btn-lg">
                  <i class="fas fa-hand-holding-heart me-2"></i>Registrar Item Encontrado
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Campo Grande Highlights -->
  <section class="py-5 bg-white">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="display-5 fw-bold">Principais Locais</h2>
        <p class="lead text-muted">Pontos Parceiros  em Campo Grande</p>
      </div>
      
      <div class="row g-4">
        <div class="col-md-4">
          <div class="city-card">
            <img src="{{ Storage::url('img-page\terminal-bandeirantes.jpeg') }}" alt="Mercadão Municipal">
            <div class="city-card-overlay">
              <h5 class="fw-bold">Terminal Bandeirantes</h5>
              <p class="small mb-0">terminal de ônibus</p>
            </div>
            </div>
          </div>
          
        <div class="col-md-4">
          <div class="city-card">
            <img src="{{ Storage::url('img-page\shopping-NORT-SUL-Plaza.jpeg') }}" alt="Shopping Norte Sul Plaza">
            <div class="city-card-overlay">
              <h5 class="fw-bold">Shopping Norte Sul Plaza</h5>
              <p class="small mb-0">Principal centro de compras</p>
            </div>
            </div>
          </div>
          
        <div class="col-md-4">
          <div class="city-card">
            <img src="{{ Storage::url('img-page\UFMS-750x410.jpg') }}" alt="UFMS">
            <div class="city-card-overlay">
              <h5 class="fw-bold">UFMS</h5>
              <p class="small mb-0">Campus universitário</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Partner Section -->
  <section class="py-5" id="parceiros">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="section-title">Seja um Parceiro</h2>
        <p class="section-subtitle">Junte-se à nossa rede de pontos de coleta</p>
      </div>
      
      <div class="row g-4 mb-5">
        <div class="col-lg-4">
          <div class="card partner-card border-0 shadow-sm h-100 text-center p-4">
            <div class="card-body">
              <div class="partner-icon mb-4 mx-auto">
                <i class="fas fa-store"></i>
              </div>
              <h4 class="fw-bold mb-3">Estabelecimentos</h4>
              <p class="text-muted mb-4">Torne seu estabelecimento um ponto oficial de coleta e devolução de itens perdidos.</p>
              <a href="{{ route('parceiro.create') }}" class="btn btn-outline-primary">
                Cadastrar Estabelecimento
              </a>
            </div>
          </div>
        </div>
        
        <div class="col-lg-4">
          <div class="card partner-card border-0 shadow-sm h-100 text-center p-4">
            <div class="card-body">
              <div class="partner-icon mb-4 mx-auto">
                <i class="fas fa-calendar-alt"></i>
              </div>
              <h4 class="fw-bold mb-3">Eventos</h4>
              <p class="text-muted mb-4">Integre o sistema de achados e perdidos ao seu evento e ofereça mais segurança.</p>
              <a href="{{ route('parceiro.create') }}" class="btn btn-outline-primary">
                Cadastrar Evento
              </a>
            </div>
          </div>
        </div>
        
        <div class="col-lg-4">
          <div class="card partner-card border-0 shadow-sm h-100 text-center p-4">
            <div class="card-body">
              <div class="partner-icon mb-4 mx-auto">
                <i class="fas fa-university"></i>
              </div>
              <h4 class="fw-bold mb-3">Instituições</h4>
              <p class="text-muted mb-4">Facilite a devolução de itens perdidos em sua instituição de forma organizada.</p>
              <a href="{{ route('parceiro.create') }}" class="btn btn-outline-primary">
                Cadastrar Instituição
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="row align-items-center bg-light rounded-4 p-4 mt-5">
        <div class="col-lg-6 mb-4 mb-lg-0">
          <img src="{{ Storage::url('img-page/trem-monumento-cg.webp') }}" 
               alt="14 de Julho" 
               class="img-fluid rounded-4 shadow-sm">
        </div>
        <div class="col-lg-6">
          <h3 class="section-title h2 mb-4">Benefícios para Parceiros</h3>
          
          <div class="benefit-item d-flex align-items-start mb-4">
            <div class="benefit-icon bg-primary text-white rounded-3 p-3">
              <i class="fas fa-chart-line"></i>
            </div>
            <div class="ms-4">
              <h5 class="fw-bold">Maior Visibilidade</h5>
              <p class="text-muted mb-0">Seu estabelecimento aparecerá em destaque no mapa e buscas do sistema.</p>
            </div>
          </div>
          
          <div class="benefit-item d-flex align-items-start mb-4">
            <div class="benefit-icon bg-primary text-white rounded-3 p-3">
              <i class="fas fa-users"></i>
            </div>
            <div class="ms-4">
              <h5 class="fw-bold">Aumento de Público</h5>
              <p class="text-muted mb-0">Receba mais visitantes interessados em recuperar ou devolver itens.</p>
            </div>
          </div>
          
          <div class="benefit-item d-flex align-items-start">
            <div class="benefit-icon bg-primary text-white rounded-3 p-3">
              <i class="fas fa-heart"></i>
            </div>
            <div class="ms-4">
              <h5 class="fw-bold">Impacto Social</h5>
              <p class="text-muted mb-0">Contribua para uma Campo Grande mais solidária e conectada.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="py-5 bg-primary text-white">
    <div class="container text-center">
      <h2 class="display-5 fw-bold mb-4">Pronto para Começar?</h2>
      <p class="lead mb-4">Junte-se aos campo-grandenses que já estão conectados através do nosso sistema</p>
      <div class="d-flex justify-content-center gap-3">
        <a href="{{ route('form.login') }}" class="btn btn-light btn-lg px-4">
          <i class="fas fa-sign-in-alt me-2"></i>Entrar
        </a>
        <a href="{{ route('registrar') }}" class="btn btn-outline-light btn-lg px-4">
          <i class="fas fa-user-plus me-2"></i>Cadastrar
        </a>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="container mt-4">
      <div class="row">
        <div class="col-lg-4 mb-4 mb-lg-0">
          <h5>Achados e Perdidos CG</h5>
          <p>Conectando pessoas e seus pertences desde 2025.</p>
          <p><i class="fas fa-map-marker-alt me-2"></i>Campo Grande - MS</p>
          <div class="d-flex gap-3 mt-4">
            <a href="#" class="footer-link">
              <i class="fab fa-facebook fa-lg"></i>
            </a>
            <a href="#" class="footer-link">
              <i class="fab fa-instagram fa-lg"></i>
            </a>
            <a href="#" class="footer-link">
              <i class="fab fa-twitter fa-lg"></i>
            </a>
          </div>
        </div>
        <div class="col-lg-4 mb-4 mb-lg-0">
          <h5>Links Úteis</h5>
          <ul class="list-unstyled">
            <li class="mb-2">
              <a href="#" class="footer-link">Termos de Uso</a>
            </li>
            <li class="mb-2">
              <a href="#" class="footer-link">Política de Privacidade</a>
            </li>
            <li class="mb-2">
              <a href="#" class="footer-link">FAQ</a>
            </li>
            <li class="mb-2">
              <a href="#parceiros" class="footer-link">Seja um Parceiro</a>
            </li>
          </ul>
        </div>
        <div class="col-lg-4">
          <h5>Contato</h5>
          <ul class="list-unstyled">
            <li class="mb-2">
              <i class="fas fa-envelope me-2"></i>
             acheaqui.cg.ms@gmail.com
            </li>
            <li class="mb-2">
              <i class="fas fa-phone me-2"></i>
              (67) 3300-0000
            </li>
            <li class="mb-2">
              <i class="fab fa-whatsapp me-2"></i>
              (67) 99999-0000
            </li>
          </ul>
        </div>
      </div>
      <hr class="my-4 bg-secondary">
      <div class="text-center">
        <p class="mb-0">&copy; 2024 Achados e Perdidos Campo Grande. Todos os direitos reservados.</p>
      </div>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Custom JS -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Navbar scroll effect
      const navbar = document.querySelector('.navbar');
      
      window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
          navbar.classList.add('scrolled');
        } else {
          navbar.classList.remove('scrolled');
        }
      });
      
      // Smooth scroll for anchor links
      document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
          e.preventDefault();
          
          const targetId = this.getAttribute('href');
          if (targetId === '#') return;
          
          const targetElement = document.querySelector(targetId);
          if (targetElement) {
            window.scrollTo({
              top: targetElement.offsetTop - 80,
              behavior: 'smooth'
            });
          }
        });
      });
    });
  </script>
</body>
</html>