<?php
include_once '../repository/authenticatedUser.php';
$user = getAuthenticatedUser();

// Verifica se o usuário está logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: /pages/index.php');
    exit();
}

// Pega o nome do usuário da sessão
$usuario = $_SESSION['usuario_nome'] ?? 'Usuário';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Abrir Chamado - HelpDesk</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8fafc;
      color: #1f2937;
      display: flex;
    }

    /* Sidebar */
    .sidebar {
      width: 260px;
      background: #111827;
      color: #fff;
      min-height: 100vh;
      padding: 20px 0;
    }
    .sidebar h2 {
      text-align: center;
      margin-bottom: 30px;
    }
    .sidebar nav {
      padding: 0;
    }
    .sidebar nav a {
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
      color: #fff;
      padding: 12px 20px;
      border-radius: 10px;
      transition: background .2s;
      font-weight: 500;
      margin-bottom: 5px;
    }
    .sidebar nav a:hover {
      background: rgba(255,255,255,.06);
    }
    .logout {
      margin: 20px;
      padding: 12px;
      width: calc(100% - 40px);
      background: #374151;
      color: #fff;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
    .logout:hover {
      background: #4b5563;
    }

    /* Conteúdo principal */
    .main {
      flex: 1;
      padding: 20px 40px;
    }
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
    }
    .header h1 {
      font-size: 20px;
      font-weight: bold;
      color: #1e40af;
    }

    /* Card do formulário */
    .card {
      background: #fff;
      border-radius: 10px;
      padding: 25px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
      max-width: 800px;
    }
    .card h2 {
      font-size: 18px;
      margin-bottom: 20px;
      color: #111827;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-group label {
      font-weight: 600;
      margin-bottom: 8px;
      display: block;
    }
    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #d1d5db;
      border-radius: 6px;
      font-size: 14px;
    }
    .form-group textarea {
      resize: vertical;
      min-height: 100px;
    }

    /* Botão principal */
    .btn-submit {
      background: #2563eb;
      color: #fff;
      border: none;
      padding: 12px 24px;
      font-size: 14px;
      font-weight: 600;
      border-radius: 6px;
      cursor: pointer;
    }
    .btn-submit:hover {
      background: #1e40af;
    }

    /* Rodapé */
    .footer {
      margin-top: 20px;
      font-size: 12px;
      color: #6b7280;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h2><?php echo htmlspecialchars($usuario); ?></h2>
    <nav>
      <a href="home.php">🏠 <span>Início</span></a>
      <a href="meus-chamados.php">📄 <span>Meus chamados</span></a>
      <a href="perfil.php">👤 <span>Perfil</span></a>
    </nav>
    <form action="logout.php" method="post">
      <button class="logout" type="submit">Desconectar</button>
    </form>
  </div>

  <!-- Conteúdo -->
  <div class="main">
    <div class="header">
      <h1>📝 Abrir Chamado</h1>
      <a href="home.php">Início</a>
    </div>

    <div class="card">
      <h2>Formulário de Novo Chamado</h2>
      <form action="/repository/registra-chamado.php" method="POST">
        <div class="form-group">
          <label for="titulo">Título do Chamado</label>
          <input type="text" id="titulo" name="titulo" required>
        </div>

        <div class="form-group">
          <label for="categoria">Categoria</label>
          <select id="categoria" name="categoria" required>
            <option value="">Selecione...</option>
            <option value="rede">Problema de Rede</option>
            <option value="hardware">Hardware</option>
            <option value="software">Software</option>
            <option value="outros">Outros</option>
          </select>
        </div>

        <div class="form-group">
          <label for="descricao">Descrição</label>
          <textarea id="descricao" name="descricao" required></textarea>
        </div>

        <button type="submit" class="btn-submit">📩 Enviar Chamado</button>
      </form>
      <p class="footer">Tempo médio de resposta: 24–48h úteis</p>
    </div>
  </div>

</body>
</html>
