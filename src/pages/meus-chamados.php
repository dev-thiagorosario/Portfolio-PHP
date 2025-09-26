<?php
include_once '../repository/authenticatedUser.php';
$user = getAuthenticatedUser(); 
 header('Location: /pages/index.php?erro=2');
?>

<?php
session_start();

$_SESSION['usuario_email'] = $_SESSION['usuario_email'] ?? 'thiago@teste.com';

$busca  = trim($_GET['q'] ?? '');
$status = $_GET['status'] ?? 'todos';

/** ==== CARREGA DADOS (DB → fallback mock) ==== */
$items = [];
try {
  // Ajuste conforme seu docker/banco:
  $pdo = new PDO("pgsql:host=127.0.0.1;port=5432;dbname=helpDesk","postgres","root",[
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  ]);

  $sql = "SELECT id, titulo, categoria, status, prioridade, criado_em
          FROM chamados
          WHERE email_usuario = :email";
  $params = [':email' => $_SESSION['usuario_email']];

  if ($status !== 'todos') { $sql .= " AND status = :status"; $params[':status'] = $status; }
  if ($busca !== '')       { $sql .= " AND (LOWER(titulo) LIKE :q OR LOWER(categoria) LIKE :q)"; $params[':q'] = "%".mb_strtolower($busca)."%"; }

  $sql .= " ORDER BY criado_em DESC";
  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);
  $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Throwable $e) {
  // MOCK se o banco não estiver disponível
  $mock = [
    ['id'=>1023,'titulo'=>'Sem Internet no setor NDI','categoria'=>'Rede','status'=>'aberto','prioridade'=>'alta','criado_em'=>'2025-09-18 10:12:00'],
    ['id'=>1018,'titulo'=>'Instalação de impressora','categoria'=>'Hardware','status'=>'em_andamento','prioridade'=>'média','criado_em'=>'2025-09-17 15:40:00'],
    ['id'=>993,'titulo'=>'Erro ao abrir sistema Polo+','categoria'=>'Software','status'=>'resolvido','prioridade'=>'alta','criado_em'=>'2025-09-12 09:05:00'],
  ];
  $items = array_values(array_filter($mock, function($x) use($status,$busca){
    $s = $status==='todos' ? true : ($x['status']===$status);
    $q = $busca==='' || str_contains(mb_strtolower($x['titulo'].' '.$x['categoria']), mb_strtolower($busca));
    return $s && $q;
  }));
}

function badge($status){
  $map = [
    'aberto'        => ['#dbeafe','#1e40af','Aberto'],
    'em_andamento'  => ['#fef3c7','#92400e','Em andamento'],
    'resolvido'     => ['#dcfce7','#065f46','Resolvido'],
    'cancelado'     => ['#fee2e2','#991b1b','Cancelado'],
  ];
  [$bg,$fg,$tx] = $map[$status] ?? ['#e5e7eb','#374151',ucfirst($status)];
  return "<span style='background:$bg;color:$fg;padding:6px 10px;border-radius:999px;font-weight:700;font-size:12px'>$tx</span>";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Meus Chamados - HelpDesk</title>
  <style>
    :root{
      --bg:#f8fafc; --card:#ffffff; --text:#111827; --muted:#6b7280;
      --primary:#2563eb; --primary-700:#1e40af; --sidebar:#111827; --sidebar-2:#1f2937;
      --border:#e5e7eb; --radius:12px;
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
    .filters{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:14px}
    .input, .select{padding:10px 12px;border:1px solid var(--border);border-radius:10px;font-size:14px;background:#fff}
    .btn{border:none;border-radius:10px;padding:10px 16px;font-weight:700;cursor:pointer}
    .btn.primary{background:var(--primary);color:#fff}
    .btn.primary:hover{background:var(--primary-700)}
    .table{width:100%;border-collapse:separate;border-spacing:0;margin-top:6px}
    .table th,.table td{padding:12px 10px;border-bottom:1px solid var(--border);text-align:left;font-size:14px}
    .table th{font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:.04em}
    .chip{padding:6px 10px;border-radius:999px;background:#eef2ff;color:#3730a3;font-weight:700;font-size:12px}
    .muted{color:var(--muted);font-size:13px}
    @media (max-width:900px){.table thead{display:none}
      .table tr{display:block;background:#fff;margin-bottom:12px;border:1px solid var(--border);border-radius:10px}
      .table td{display:flex;justify-content:space-between;border-bottom:0;padding:10px 14px}
      .table td::before{content:attr(data-label);font-weight:700;color:#374151}
    }
  </style>
</head>
<body>
  <aside class="sidebar">
    <div class="brand">💬 HelpDesk</div>
    <nav>
      <ul class="nav">
        <li><a href="index.php">🏠 Início</a></li>
        <li><a class="active" href="meus-chamados.php">📑 Meus chamados</a></li>
        <li><a href="perfil.php">👤 Perfil</a></li>
      </ul>
    </nav>
    <button class="logout">Desconectar</button>
  </aside>

  <main class="main">
    <div class="top">
      <h1>📑 Meus chamados</h1>
      <a class="muted" href="index.php">Início</a>
    </div>

    <section class="card">
      <form class="filters" method="get">
        <input class="input" type="search" name="q" placeholder="Buscar por título ou categoria..." value="<?= htmlspecialchars($busca) ?>" />
        <select class="select" name="status">
          <?php
            $opts = ['todos'=>'Todos','aberto'=>'Aberto','em_andamento'=>'Em andamento','resolvido'=>'Resolvido','cancelado'=>'Cancelado'];
            foreach($opts as $k=>$v){
              $sel = $status===$k ? 'selected' : '';
              echo "<option value='$k' $sel>$v</option>";
            }
          ?>
        </select>
        <button class="btn primary" type="submit">Filtrar</button>
        <a class="btn" href="abrir-chamado.php" style="background:#10b981;color:#fff">➕ Abrir chamado</a>
      </form>

      <?php if (empty($items)): ?>
        <p class="muted">Nenhum chamado encontrado.</p>
      <?php else: ?>
      <div class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th>#ID</th>
              <th>Título</th>
              <th>Categoria</th>
              <th>Prioridade</th>
              <th>Status</th>
              <th>Criado em</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $c): ?>
              <tr>
                <td data-label="#ID"><?= htmlspecialchars($c['id']) ?></td>
                <td data-label="Título"><?= htmlspecialchars($c['titulo']) ?></td>
                <td data-label="Categoria"><span class="chip"><?= htmlspecialchars($c['categoria']) ?></span></td>
                <td data-label="Prioridade"><span class="chip" style="background:#fff7ed;color:#9a3412"><?= htmlspecialchars(ucfirst($c['prioridade'])) ?></span></td>
                <td data-label="Status"><?= badge($c['status']) ?></td>
                <td data-label="Criado em"><?= date('d/m/Y H:i', strtotime($c['criado_em'])) ?></td>
                <td data-label=""><a class="muted" href="chamado.php?id=<?= urlencode($c['id']) ?>">Ver detalhes ›</a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>
