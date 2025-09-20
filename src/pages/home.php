<?php
// home.php
session_start();

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
  <title>HelpDesk — Início</title>
  <style>
    :root{
      --bg: #f4f6fa;
      --panel:#ffffff;
      --panel-2:#f9fafb;
      --text:#1f2937;
      --muted:#6b7280;
      --primary:#4f6fa8;
      --primary-hover:#3d5c92;
      --accent:#16a34a;
      --accent-hover:#12833d;
      --radius:16px;
      --shadow: 0 8px 26px rgba(0,0,0,.07);
      --border:#e5e7eb;
      --sidebar:#111827;
      --sidebar-text:#e5e7eb;
      --sidebar-muted:#9ca3af;
    }

    *{ box-sizing: border-box; }
    html, body { height: 100%; }
    body{
      margin:0;
      font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
      background: var(--bg);
      color: var(--text);
      display:flex;
      min-height:100vh;
    }

    /* Sidebar */
    .sidebar{
      width: 250px;
      background: var(--sidebar);
      color: var(--sidebar-text);
      padding: 20px 16px;
      display:flex;
      flex-direction:column;
      gap: 14px;
    }
    .brand{
      display:flex;
      align-items:center;
      gap:10px;
      margin-bottom: 10px;
    }
    .brand a{
      color:#c7d2fe;
      font-weight:800;
      font-size:1.1rem;
      text-decoration:none;
    }
    .avatar{
      width:56px; height:56px;
      border-radius:50%;
      background: linear-gradient(135deg,#334155,#64748b);
      display:grid; place-items:center;
      font-weight:700;
      color:#e5e7eb;
    }
    .user{
      font-size:.95rem;
      color:#e5e7eb;
    }
    nav a{
      display:flex; align-items:center; gap:10px;
      text-decoration:none;
      color: var(--sidebar-text);
      padding:10px 12px;
      border-radius:10px;
      transition: background .2s;
      font-weight:500;
    }
    nav a:hover{ background: rgba(255,255,255,.06); }
    .logout{
      margin-top:auto;
      width:100%;
      background:#334155;
      border:1px solid #475569;
      color:#e5e7eb;
      padding:12px;
      border-radius:10px;
      cursor:pointer;
      transition: background .2s;
    }
    .logout:hover{ background:#293241; }

    /* Main */
    .main{
      flex:1;
      padding: 20px;
      display:flex;
      flex-direction:column;
      gap:16px;
    }

    .topbar{
      background: var(--panel);
      border:1px solid var(--border);
      border-radius: 14px;
      padding: 12px 16px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      box-shadow: var(--shadow);
    }
    .topbar .title a{
      text-decoration:none;
      color: var(--primary);
      font-weight:800;
      font-size:1.1rem;
    }
    .topbar .title a:hover{ color: var(--primary-hover); }
    .breadcrumb{
      color:var(--muted);
      font-size:.95rem;
    }

    .content{
      display:grid;
      grid-template-columns: repeat(2, minmax(240px, 1fr));
      gap:16px;
    }

    .card{
      background: var(--panel-2);
      border:1px solid var(--border);
      border-radius:16px;
      padding:20px;
      box-shadow: var(--shadow);
      display:flex;
      flex-direction:column;
      gap:12px;
      transition: transform .18s ease, box-shadow .18s ease;
    }
    .card:hover{ transform: translateY(-2px); box-shadow: 0 12px 34px rgba(0,0,0,.1); }

    .card h2{
      margin:0;
      font-size:1.1rem;
    }
    .card p{
      margin:0;
      color:var(--muted);
      font-size:.96rem;
      line-height:1.4rem;
    }

    .btn{
      align-self:flex-start;
      margin-top:6px;
      padding: 12px 14px;
      border-radius:10px;
      border:0;
      font-weight:600;
      cursor:pointer;
      transition: background .2s, transform .02s;
    }
    .btn-primary{
      background: var(--primary); color:#fff;
    }
    .btn-primary:hover{ background: var(--primary-hover); }
    .btn-success{
      background: var(--accent); color:#fff;
    }
    .btn-success:hover{ background: var(--accent-hover); }
    .btn:active{ transform: translateY(1px); }

    .muted{
      color:var(--muted);
      font-size:.92rem;
    }

    /* Responsivo */
    @media (max-width: 900px){
      .sidebar{ width: 88px; }
      .brand .txt, .user{ display:none; }
      nav a{ justify-content:center; }
      .content{ grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <aside class="sidebar" aria-label="Menu lateral">
    <div class="brand">
      <div class="avatar" aria-hidden="true">U</div>
      <div class="txt">
        <div class="user"><?= htmlspecialchars($usuario) ?></div>
        <div class="muted">Perfil de usuário</div>
      </div>
    </div>

    <nav>
      <a href="home.php" aria-current="page">🏠 <span>Início</span></a>
      <a href="meus-chamados.php">📄 <span>Meus chamados</span></a>
      <a href="perfil.php">👤 <span>Perfil</span></a>
    </nav>

    <form action="logout.php" method="post">
      <button class="logout" type="submit">Desconectar</button>
    </form>
  </aside>

  <!-- Main -->
  <section class="main">
    <div class="topbar">
      <div class="title"><a href="home.php">📌 HelpDesk</a></div>
      <div class="breadcrumb">Início</div>
    </div>

    <div class="content">
      <!-- Card A: Abrir chamado -->
      <article class="card">
        <h2>🆕 Abrir novo chamado</h2>
        <p>Relate um problema ou solicitação de suporte. Informe o assunto e descreva o ocorrido.</p>
        <a class="btn btn-success" href="abrir-chamado.php">Abrir chamado</a>
        <span class="muted">Tempo médio de resposta: 24–48h úteis</span>
      </article>

      <!-- Card B: Consultar chamados -->
      <article class="card">
        <h2>🔎 Consultar chamados</h2>
        <p>Acompanhe o status dos chamados já abertos, veja detalhes, histórico e atualizações.</p>
        <a class="btn btn-primary" href="meus-chamados.php">Ver meus chamados</a>
        <span class="muted">Você receberá notificações por e-mail quando houver mudanças</span>
      </article>
    </div>
  </section>

</body>
</html>
