<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <!-- Link para o Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Cadastro de Usuário</h2>
    
    <!-- Formulário com a validação do Laravel -->
    <form action="{{ route('criar-usuario') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Nome -->
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" value="{{ old('nome') }}" required>
            @error('nome')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- E-mail -->
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Telefone -->
        <div class="mb-3">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="text" class="form-control @error('telefone') is-invalid @enderror" id="telefone" name="telefone" value="{{ old('telefone') }}" required>
            @error('telefone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Senha -->
        <div class="mb-3">
            <label for="senha" class="form-label">Senha</label>
            <input type="password" class="form-control @error('senha') is-invalid @enderror" id="senha" name="senha" required>
            @error('senha')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Foto (opcional) -->
        <div class="mb-3">
            <label for="foto" class="form-label">Foto</label>
            <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto" name="foto">
            @error('foto')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- CPF -->
        <div class="mb-3">
            <label for="cpf" class="form-label">CPF</label>
            <input type="text" class="form-control @error('cpf') is-invalid @enderror" id="cpf" name="cpf" value="{{ old('cpf') }}" required>
            @error('cpf')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Botão de Envio -->
        <button type="submit" class="btn btn-primary">Cadastrar</button>
    </form>
</div>

<!-- Script do Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
