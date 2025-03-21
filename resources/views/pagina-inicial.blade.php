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
    }

    .navbar.scrolled {
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand {
      font-weight: 600;
      color: var(--primary-color) !important;
    }

    .nav-link {
      color: var(--text-color) !important;
      font-weight: 500;
      position: relative;
      transition: all 0.3s ease;
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
    
    .feature-card {
      border-radius: 1rem;
      border: none;
      transition: all 0.3s ease;
      height: 100%;
      background: white;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      overflow: hidden;
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
    
    .stats-number {
      font-size: 3rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }
    
    .stats-label {
      font-size: 1.1rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      opacity: 0.9;
    }
    
    .how-works-section {
      background-color: var(--secondary-color);
      position: relative;
    }

    .how-works-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(45deg, #f1f5f9 25%, transparent 25%) -40px 0,
                  linear-gradient(-45deg, #f1f5f9 25%, transparent 25%) -40px 0;
      background-size: 80px 80px;
      opacity: 0.5;
    }
    
    .step-card {
      background: white;
      border-radius: 1rem;
      padding: 2rem;
      margin-bottom: 1.5rem;
      position: relative;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .step-number {
      position: absolute;
      left: -15px;
      top: 50%;
      transform: translateY(-50%);
      width: 3rem;
      height: 3rem;
      background: var(--primary-color);
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 1.25rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .city-card {
      position: relative;
      overflow: hidden;
      border-radius: 1rem;
      margin-bottom: 1.5rem;
      height: 250px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .city-card img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }
    
    .city-card:hover img {
      transform: scale(1.1);
    }
    
    .city-card-overlay {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
      color: white;
      padding: 2rem 1.5rem 1.5rem;
    }

    .partner-section {
      background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }
    
    .partner-card {
      background: white;
      border-radius: 1rem;
      padding: 2rem;
      height: 100%;
      transition: all 0.3s ease;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .partner-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 20px rgba(0, 0, 0, 0.1);
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
      margin: 0 auto 1.5rem;
      font-size: 2rem;
    }

    .footer {
      background: #1e293b;
      color: #cbd5e1;
      padding: 4rem 0 2rem;
    }

    .footer h5 {
      color: white;
      margin-bottom: 1.5rem;
    }

    .footer-link {
      color: #cbd5e1;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .footer-link:hover {
      color: white;
    }

    .search-form {
      position: relative;
    }

    .search-input {
      padding: 1rem 1.5rem;
      border-radius: 0.75rem;
      border: 2px solid #e2e8f0;
      width: 100%;
      font-size: 1.1rem;
    }

    .search-button {
      position: absolute;
      right: 0.5rem;
      top: 50%;
      transform: translateY(-50%);
      background: var(--primary-color);
      color: white;
      border: none;
      padding: 0.75rem 1.5rem;
      border-radius: 0.5rem;
      font-weight: 500;
    }

    @media (max-width: 768px) {
      .hero-section {
        padding: 4rem 0;
      }
      
      .stats-box {
        margin-bottom: 1rem;
      }
      
      .step-card {
        padding: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#">
        <i class="fas fa-search me-2"></i>
        Achados e Perdidos CG
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
            <a class="nav-link" href="{{ route('form.login') }}">Entrar</a>
          </li>
          <li class="nav-item ms-2">
            <a class="btn btn-primary" href="{{ route('registrar') }}">Cadastrar</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="container">
      <div class="row align-items-center">
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
            <span class="text-light ms-2 mb-2">Categorias populares</span>
          </div>
        </div>
        <div class="col-lg-5 d-none d-lg-block">
          <img src="https://www.campogrande.ms.gov.br/wp-content/uploads/2022/05/0F0A2139-2-scaled.jpg" 
               alt="Campo Grande" 
               class="img-fluid rounded-3 shadow-lg" 
               style="object-fit: cover; height: 400px; width: 100%; filter: brightness(1.1) contrast(1.1);">
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
              <i class="fas fa-bell"></i>
            </div>
            <h4 class="fw-bold mb-3">Notificações</h4>
            <p>Receba alertas quando itens similares aos que você perdeu forem encontrados na cidade.</p>
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
  <section class="how-works-section py-5" id="como-funciona">
    <div class="container position-relative">
      <div class="text-center mb-5">
        <h2 class="display-5 fw-bold">Como Funciona</h2>
        <p class="lead text-muted">Um processo simples e seguro para recuperar seus pertences</p>
      </div>
      
      <div class="row g-4">
        <div class="col-lg-6">
          <div class="p-4 rounded-3 bg-white shadow-sm mb-4">
            <h3 class="fw-bold mb-4">
              <i class="fas fa-search text-primary me-2"></i>
              Perdeu algo?
            </h3>
          
            <div class="step-card">
            <div class="step-number">1</div>
              <h5>Faça seu cadastro</h5>
              <p class="mb-0">Crie uma conta gratuita para acessar todas as funcionalidades.</p>
          </div>
          
            <div class="step-card">
            <div class="step-number">2</div>
              <h5>Registre o item perdido</h5>
              <p class="mb-0">Descreva o objeto, local e data aproximada da perda.</p>
          </div>
          
            <div class="step-card">
            <div class="step-number">3</div>
              <h5>Receba notificações</h5>
              <p class="mb-0">Seja alertado quando itens similares forem encontrados.</p>
          </div>
          
          <div class="text-center mt-4">
            <a href="{{ route('form.login') }}" class="btn btn-primary btn-lg">
              <i class="fas fa-search me-2"></i>Reportar Item Perdido
            </a>
            </div>
          </div>
        </div>
        
        <div class="col-lg-6">
          <div class="p-4 rounded-3 bg-white shadow-sm mb-4">
            <h3 class="fw-bold mb-4">
              <i class="fas fa-hand-holding-heart text-primary me-2"></i>
              Encontrou algo?
            </h3>
          
            <div class="step-card">
            <div class="step-number">1</div>
              <h5>Cadastre-se</h5>
              <p class="mb-0">Crie sua conta para registrar o item encontrado.</p>
          </div>
          
            <div class="step-card">
            <div class="step-number">2</div>
              <h5>Descreva o item</h5>
              <p class="mb-0">Adicione fotos e informações detalhadas do objeto.</p>
          </div>
          
            <div class="step-card">
            <div class="step-number">3</div>
              <h5>Entregue com segurança</h5>
              <p class="mb-0">Use nossos pontos parceiros para uma devolução segura.</p>
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
  </section>

  <!-- Campo Grande Highlights -->
  <section class="py-5 bg-white">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="display-5 fw-bold">Principais Locais</h2>
        <p class="lead text-muted">Pontos com maior frequência de itens perdidos em Campo Grande</p>
      </div>
      
      <div class="row g-4">
        <div class="col-md-4">
          <div class="city-card">
            <img src="https://www.campogrande.ms.gov.br/wp-content/uploads/2023/05/mercadao.jpg" alt="Mercadão Municipal">
            <div class="city-card-overlay">
              <h5 class="fw-bold">Mercadão Municipal</h5>
              <p class="small mb-0">Centro comercial histórico</p>
            </div>
            </div>
          </div>
          
        <div class="col-md-4">
          <div class="city-card">
            <img src="https://www.campogrande.ms.gov.br/wp-content/uploads/2022/03/Shopping-Norte-Sul-Plaza.jpg" alt="Shopping Norte Sul Plaza">
            <div class="city-card-overlay">
              <h5 class="fw-bold">Shopping Norte Sul Plaza</h5>
              <p class="small mb-0">Principal centro de compras</p>
            </div>
            </div>
          </div>
          
        <div class="col-md-4">
          <div class="city-card">
            <img src="https://www.campogrande.ms.gov.br/wp-content/uploads/2022/01/20191212_Cidade_Morena_dia_05_1100px-14.jpg" alt="UFMS">
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
  <section class="partner-section py-5" id="parceiros">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="display-5 fw-bold">Seja um Parceiro</h2>
        <p class="lead text-muted">Junte-se à nossa rede de pontos de coleta</p>
      </div>
      
      <div class="row g-4">
        <div class="col-lg-4">
          <div class="partner-card text-center">
            <div class="partner-icon">
              <i class="fas fa-store"></i>
            </div>
            <h4 class="fw-bold">Estabelecimentos</h4>
            <p>Torne seu estabelecimento um ponto oficial de coleta e devolução.</p>
            <a href="{{ route('form.login') }}" class="btn btn-outline-primary">
              Cadastrar Estabelecimento
            </a>
          </div>
        </div>
        
        <div class="col-lg-4">
          <div class="partner-card text-center">
            <div class="partner-icon">
              <i class="fas fa-calendar-alt"></i>
            </div>
            <h4 class="fw-bold">Eventos</h4>
            <p>Integre o sistema de achados e perdidos ao seu evento.</p>
            <a href="{{ route('form.login') }}" class="btn btn-outline-primary">
              Cadastrar Evento
            </a>
          </div>
        </div>
        
        <div class="col-lg-4">
          <div class="partner-card text-center">
            <div class="partner-icon">
              <i class="fas fa-university"></i>
            </div>
            <h4 class="fw-bold">Instituições</h4>
            <p>Facilite a devolução de itens em sua instituição.</p>
            <a href="{{ route('form.login') }}" class="btn btn-outline-primary">
              Cadastrar Instituição
            </a>
          </div>
        </div>
      </div>

      <div class="row mt-5 align-items-center">
        <div class="col-lg-6">
          <img src="https://www.campogrande.ms.gov.br/wp-content/uploads/2022/05/14_de_Julho_Revitalizada_compressed.jpg" 
               alt="14 de Julho" 
               class="img-fluid rounded-3 shadow">
        </div>
        <div class="col-lg-6">
          <h3 class="fw-bold mb-4">Benefícios para Parceiros</h3>
          
          <div class="d-flex align-items-start mb-4">
            <div class="flex-shrink-0">
              <div class="bg-primary text-white rounded-circle p-3">
                <i class="fas fa-chart-line"></i>
              </div>
            </div>
            <div class="ms-4">
              <h5 class="fw-bold">Maior Visibilidade</h5>
              <p class="mb-0">Seu estabelecimento aparecerá em destaque no mapa e buscas do sistema.</p>
            </div>
          </div>
          
          <div class="d-flex align-items-start mb-4">
            <div class="flex-shrink-0">
              <div class="bg-primary text-white rounded-circle p-3">
                <i class="fas fa-users"></i>
              </div>
            </div>
            <div class="ms-4">
              <h5 class="fw-bold">Aumento de Público</h5>
              <p class="mb-0">Receba mais visitantes interessados em recuperar ou devolver itens.</p>
            </div>
          </div>
          
          <div class="d-flex align-items-start">
            <div class="flex-shrink-0">
              <div class="bg-primary text-white rounded-circle p-3">
                <i class="fas fa-heart"></i>
              </div>
            </div>
            <div class="ms-4">
              <h5 class="fw-bold">Impacto Social</h5>
              <p class="mb-0">Contribua para uma Campo Grande mais solidária e conectada.</p>
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
    <div class="container">
      <div class="row">
        <div class="col-lg-4 mb-4 mb-lg-0">
          <h5>Achados e Perdidos CG</h5>
          <p>Conectando pessoas e seus pertences desde 2023.</p>
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
              contato@achadosperdidoscg.com.br
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