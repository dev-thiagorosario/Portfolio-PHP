<?php
include_once '../repository/access_validator.php';
$user = getAuthenticatedUser();
?>

<?php

$_SESSION['usuario_nome']  = $_SESSION['usuario_nome']  ?? 'Thiago Rosario';
$_SESSION['usuario_email'] = $_SESSION['usuario_email'] ?? 'thiago@teste.com';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Perfil - HelpDesk</title>
  <style>
    :root{
      --bg:#f8fafc; --card:#ffffff; --text:#111827; --muted:#6b7280;
      --primary:#2563eb; --primary-700:#1e40af; --sidebar:#111827; --sidebar-2:#1f2937;
      --border:#e5e7eb; --ok:#059669; --warn:#d97706; --bad:#dc2626;
      --radius:12px;
    }
    *{box-sizing:border-box} body{margin:0;font-family:Inter,system-ui,Segoe UI,Roboto,Arial,sans-serif;background:var(--bg);color:var(--text);display:flex}
    .sidebar{width:260px;min-height:100vh;background:var(--sidebar);color:#fff;padding:20px 0}
    .brand{display:flex;align-items:center;gap:10px;font-weight:700;margin:0 20px 24px}
    .nav{list-style:none;margin:0;padding:0}
    .nav a{display:flex;gap:10px;align-items:center;padding:12px 20px;color:#cbd5e1;text-decoration:none;border-left:3px solid transparent}
    .nav a:hover{background:var(--sidebar-2);color:#fff}
    .nav a.active{background:var(--sidebar-2);color:#fff;border-left-color:var(--primary)}
    .logout{margin:20px;padding:12px;width:calc(100% - 40px);background:#374151;color:#fff;border:none;border-radius:8px;cursor:pointer}
    .logout:hover{background:#4b5563}
    .main{flex:1;padding:28px 40px}
    .top{display:flex;justify-content:space-between;align-items:center;margin-bottom:22px}
    .top h1{font-size:20px;font-weight:800;color:var(--primary-700);margin:0}
    .card{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);box-shadow:0 8px 20px rgba(0,0,0,.04);padding:22px}
    .grid{display:grid;grid-template-columns:1fr;gap:16px}
    .row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
    @media (max-width:900px){.row{grid-template-columns:1fr}}
    label{font-weight:600;margin-bottom:8px;display:block}
    input{width:100%;padding:12px;border:1px solid var(--border);border-radius:10px;font-size:14px}
    .muted{color:var(--muted);font-size:13px}
    .actions{display:flex;gap:10px;margin-top:8px}
    .btn{border:none;border-radius:10px;padding:12px 18px;font-weight:700;cursor:pointer}
    .btn.primary{background:var(--primary);color:#fff}
    .btn.primary:hover{background:var(--primary-700)}
    .btn.ghost{background:#f3f4f6}
  </style>
</head>
<body>
  <aside class="sidebar">
    <div class="brand">💬 HelpDesk</div>
    <nav>
      <ul class="nav">
        <li><a href="home.php">🏠 Início</a></li>
        <li><a href="meus-chamados.php">📑 Meus chamados</a></li>
        <li><a class="active" href="perfil.php">👤 Perfil</a></li>
      </ul>
    </nav>
    <button class="logout">Desconectar</button>
  </aside>

  <main class="main">
    <div class="top">
      <h1>👤 Perfil de usuário</h1>
      <a class="muted" href="home.php">Início</a>
    </div>

    <section class="card">
      <form action="perfil_salvar.php" method="POST" class="grid" autocomplete="off">
        <div class="row">
          <div>
            <label for="nome">Nome completo</label>
            <input id="nome" name="nome" value="<?= htmlspecialchars($_SESSION['usuario_nome']) ?>" required />
          </div>
          <div>
            <label for="email">E-mail</label>
            <input id="email" name="email" type="email" value="<?= htmlspecialchars($_SESSION['usuario_email']) ?>" readonly />
            <div class="muted">O e-mail é usado para login e notificações.</div>
          </div>
        </div>

        <div class="row">
          <div>
            <label for="senha">Nova senha</label>
            <input id="senha" name="senha" type="password" placeholder="••••••••" />
            <div class="muted">Deixe em branco para manter a senha atual.</div>
          </div>
          <div>
            <label for="senha2">Confirmar nova senha</label>
            <input id="senha2" name="senha2" type="password" placeholder="••••••••" />
          </div>
        </div>

        <div class="actions">
          <button type="submit" class="btn primary">💾 Salvar alterações</button>
          <a class="btn ghost" href="index.php">Cancelar</a>
        </div>
      </form>
    </section>
  </main>
</body>
</html>
