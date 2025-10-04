<?php
// pages/perfil-admin.php
declare(strict_types=1);

include_once '../../repository/authenticatedUser.php';
$user = getAuthenticatedUser();

// ======= CSRF =======
if (empty($_SESSION['csrf'])) {
  $_SESSION['csrf'] = bin2hex(random_bytes(16));
}
function check_csrf(): void {
  if (($_POST['csrf'] ?? '') !== ($_SESSION['csrf'] ?? '')) {
    http_response_code(403);
    exit('CSRF inválido.');
  }
}

// ======= STUB de persistência (apenas mensagem) =======
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  check_csrf();
  // Aqui você conecta no seu UPDATE de perfil/segurança/preferências
  // Ex.: update usuarios set nome = :n, email = :e ... where id = :id
  $secao = $POST['_secao'] ?? 'perfil';
  $msg = "Informações de '$secao' recebidas. (Implemente a persistência no backend)";
}

// ======= Dados simulados (traga do seu $user/banco se preferir) =======
$nomeExibicao  = $_SESSION['usuario_nome'] ?? ($user['nome'] ?? 'Administrador');
$emailExibicao = $_SESSION['usuario_email'] ?? ($user['email'] ?? 'admin@exemplo.com');
$departamento  = $user['departamento'] ?? 'TI — Suporte';
$papel         = 'Administrador';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>HelpDesk — Perfil do Administrador</title>
<style>
  :root{
    --bg:#f8fafc; --card:#ffffff; --text:#111827; --muted:#6b7280;
    --primary:#2563eb; --primary-700:#1e40af; --sidebar:#111827; --sidebar-2:#1f2937;
    --border:#e5e7eb; --radius:12px; --shadow:0 8px 20px rgba(0,0,0,.06);
    --ok:#10b981; --warn:#f59e0b; --danger:#ef4444;
  }
  *{box-sizing:border-box}
  body{margin:0;font-family:Inter,system-ui,Segoe UI,Roboto,Arial,sans-serif;background:var(--bg);color:var(--text);display:flex}
  .sidebar{width:260px;min-height:100vh;background:var(--sidebar);color:#fff;padding:20px 0}
  .brand{display:flex;align-items:center;gap:10px;font-weight:800;margin:0 20px 24px}
  .nav{list-style:none;margin:0;padding:0}
  .nav a{display:flex;gap:10px;align-items:center;padding:12px 20px;color:#cbd5e1;text-decoration:none;border-left:3px solid transparent}
  .nav a:hover{background:var(--sidebar-2);color:#fff}
  .nav a.active{background:var(--sidebar-2);color:#fff;border-left-color:var(--primary)}
  .logout{margin:20px;padding:12px;width:calc(100% - 40px);background:#374151;color:#fff;border:none;border-radius:8px;cursor:pointer}
  .logout:hover{background:#4b5563}
  .main{flex:1;padding:28px 40px;display:flex;flex-direction:column;gap:18px}
  .top{display:flex;justify-content:space-between;align-items:center}
  .top h1{font-size:20px;font-weight:800;color:var(--primary-700);margin:0}
  .muted{color:var(--muted);font-size:14px}
  .grid{display:grid;grid-template-columns:2fr 1fr;gap:14px}
  .card{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);padding:16px}
  .card h2{margin:0 0 10px 0;font-size:16px}
  .row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
  .row-1{display:grid;grid-template-columns:1fr;gap:12px}
  .field{display:flex;flex-direction:column;gap:6px}
  .label{font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:.04em}
  .input,.select,.file{padding:10px 12px;border:1px solid var(--border);border-radius:10px;font-size:14px;background:#fff}
  .btn{border:none;border-radius:10px;padding:10px 16px;font-weight:700;cursor:pointer}
  .btn.primary{background:var(--primary);color:#fff}
  .btn.primary:hover{background:var(--primary-700)}
  .btn.ok{background:var(--ok);color:#fff}
  .btn.warn{background:var(--warn);color:#111827}
  .msg{padding:10px 12px;border:1px solid var(--border);background:#ecfeff;color:#0369a1;border-radius:10px}
  .avatar{width:72px;height:72px;border-radius:999px;background:linear-gradient(135deg,#60a5fa,#1e40af);display:grid;place-items:center;color:#fff;font-weight:800;font-size:22px}
  .stack{display:flex;align-items:center;gap:12px}
  .switch{display:flex;align-items:center;gap:10px}
  .switch input{width:40px;height:22px}
  @media (max-width:1100px){.grid{grid-template-columns:1fr}}
</style>
</head>
<body>
  <aside class="sidebar">
    <div class="brand">🛠️ HelpDesk — Admin</div>
    <nav>
      <ul class="nav">
        <li><a href="home-admin.php">📋 Painel</a></li>
        <li><a class="active" href="perfil-admin.php">👤 Perfil</a></li>
      </ul>
    </nav>
    <form action="../logout.php" method="post">
      <button class="logout" type="submit">Desconectar</button>
    </form>
  </aside>

  <main class="main">
    <div class="top">
      <h1>👤 Perfil do Administrador</h1>
      <div class="muted">Olá, <?= htmlspecialchars($nomeExibicao) ?></div>
    </div>

    <?php if ($msg): ?>
      <div class="msg"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <section class="grid">
      <!-- Coluna principal -->
      <div class="col">
        <!-- Dados da conta -->
        <article class="card">
          <h2>Dados da conta</h2>
          <div class="stack" style="margin-bottom:12px">
            <div class="avatar" aria-hidden="true"><?= mb_strtoupper(mb_substr($nomeExibicao,0,1)) ?></div>
            <div>
              <div style="font-weight:800"><?= htmlspecialchars($nomeExibicao) ?></div>
              <div class="muted"><?= htmlspecialchars($papel) ?> • <?= htmlspecialchars($departamento) ?></div>
            </div>
          </div>
          <form method="post" action="perfil-admin.php" enctype="multipart/form-data">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
            <input type="hidden" name="__secao" value="perfil">
            <div class="row">
              <div class="field">
                <label class="label">Nome</label>
                <input class="input" type="text" name="nome" value="<?= htmlspecialchars($nomeExibicao) ?>" required>
              </div>
              <div class="field">
                <label class="label">E-mail</label>
                <input class="input" type="email" name="email" value="<?= htmlspecialchars($emailExibicao) ?>" required>
              </div>
            </div>
            <div class="row">
              <div class="field">
                <label class="label">Departamento</label>
                <input class="input" type="text" name="departamento" value="<?= htmlspecialchars($departamento) ?>">
              </div>
              <div class="field">
                <label class="label">Papel</label>
                <input class="input" type="text" name="papel" value="<?= htmlspecialchars($papel) ?>" readonly>
              </div>
            </div>
            <div class="row">
              <div class="field">
                <label class="label">Avatar (imagem)</label>
                <input class="file" type="file" name="avatar" accept="image/*">
              </div>
              <div class="field">
                <label class="label">Assinatura (opcional)</label>
                <input class="input" type="text" name="assinatura" placeholder="Ex.: 'Atenciosamente, Equipe HelpDesk'">
              </div>
            </div>
            <div style="margin-top:12px;display:flex;gap:10px">
              <button class="btn primary" type="submit">Salvar alterações</button>
            </div>
          </form>
        </article>

        <!-- Preferências -->
        <article class="card">
          <h2>Preferências</h2>
          <form method="post" action="perfil-admin.php">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
            <input type="hidden" name="__secao" value="preferencias">
            <div class="row">
              <div class="field">
                <label class="label">Idioma</label>
                <select class="select" name="idioma">
                  <option value="pt-BR" selected>Português (Brasil)</option>
                  <option value="en-US">English (US)</option>
                </select>
              </div>
              <div class="field">
                <label class="label">Tema</label>
                <select class="select" name="tema">
                  <option value="claro" selected>Claro</option>
                  <option value="escuro">Escuro</option>
                </select>
              </div>
            </div>
            <div class="row-1">
              <div class="switch">
                <input type="checkbox" id="notifica_email" name="notifica_email" checked>
                <label for="notifica_email">Receber notificações de chamados por e-mail</label>
              </div>
              <div class="switch">
                <input type="checkbox" id="notifica_push" name="notifica_push">
                <label for="notifica_push">Receber notificações push (browser)</label>
              </div>
            </div>
            <div style="margin-top:12px">
              <button class="btn primary" type="submit">Salvar preferências</button>
            </div>
          </form>
        </article>
      </div>

      <!-- Coluna lateral -->
      <div class="col">
        <!-- Segurança -->
        <article class="card">
          <h2>Segurança</h2>
          <form method="post" action="perfil-admin.php" autocomplete="off">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
            <input type="hidden" name="__secao" value="seguranca">
            <div class="field">
              <label class="label">Senha atual</label>
              <input class="input" type="password" name="senha_atual" placeholder="••••••••" required>
            </div>
            <div class="field">
              <label class="label">Nova senha</label>
              <input class="input" type="password" name="nova_senha" placeholder="Mínimo 8 caracteres" required>
            </div>
            <div class="field">
              <label class="label">Confirmar nova senha</label>
              <input class="input" type="password" name="confirma_senha" placeholder="Repita a nova senha" required>
            </div>
            <div class="switch" style="margin-top:10px">
              <input type="checkbox" id="mfa" name="mfa">
              <label for="mfa">Habilitar Autenticação em Duas Etapas (2FA)</label>
            </div>
            <div style="margin-top:12px;display:flex;gap:8px;flex-wrap:wrap">
              <button class="btn ok" type="submit">Atualizar senha</button>
              <button class="btn warn" type="button" onclick="alert('Geração de códigos de recuperação: implemente no backend.')">Gerar códigos de recuperação</button>
            </div>
          </form>
        </article>

        <!-- Acesso Rápido -->
        <article class="card">
          <h2>Acesso rápido</h2>
          <div class="row-1">
            <a class="btn primary" href="home-admin.php">Ir para o Painel</a>
            <a class="btn" style="border:1px solid var(--border)" href="../logs.php">Ver Logs (admin)</a>
          </div>
        </article>
      </div>
    </section>
  </main>
</body>
</html>