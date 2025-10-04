<?php
// pages/home-admin.php
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

// ======= FILTROS GET (mantidos) =======
$busca  = trim($_GET['q'] ?? '');
$status = $_GET['status'] ?? 'aberto'; // padrão: abertos
$priori = $_GET['prioridade'] ?? 'todas';

// ======= MOCK (somente para visualização local, sem BD) =======
const MOCK_DATA = [
  ['id'=>1201,'titulo'=>'Sem internet — Bloco A','categoria'=>'Rede','status'=>'aberto','prioridade'=>'alta','criado_em'=>'2025-09-26 10:02:00','email_usuario'=>'ana@teste.com'],
  ['id'=>1199,'titulo'=>'Instalação LibreOffice','categoria'=>'Software','status'=>'em_andamento','prioridade'=>'média','criado_em'=>'2025-09-25 16:31:00','email_usuario'=>'joao@teste.com'],
  ['id'=>1183,'titulo'=>'Troca de teclado','categoria'=>'Hardware','status'=>'resolvido','prioridade'=>'baixa','criado_em'=>'2025-09-20 09:11:00','email_usuario'=>'maria@teste.com'],
];

// ======= STUBS: pontos de integração com o banco =======
/**
 * TODO: Substituir por SELECT real no seu banco usando os filtros.
 * Retorne o array de chamados já filtrado e ordenado.
 */
function listarChamados(array $filtros): array {
  $busca  = $filtros['q'];
  $status = $filtros['status'];
  $priori = $filtros['prioridade'];

  // Simulação local (sem BD): filtra e ordena MOCK_DATA
  $items = array_values(array_filter(MOCK_DATA, function($x) use($status,$busca,$priori){
    $okStatus = ($status==='todos') ? true : ($x['status'] === $status);
    $okPri    = ($priori==='todas') ? true : (mb_strtolower($x['prioridade']) === mb_strtolower($priori));
    $haystack = mb_strtolower($x['titulo'].' '.$x['categoria'].' '.$x['email_usuario']);
    $okBusca  = ($busca==='') || str_contains($haystack, mb_strtolower($busca));
    return $okStatus && $okPri && $okBusca;
  }));

  usort($items, function($a,$b){
    // mesma ordenação da sua query original
    $ord = ['aberto'=>1,'em_andamento'=>2,'resolvido'=>3,'cancelado'=>4];
    $sa = $ord[$a['status']] ?? 5;
    $sb = $ord[$b['status']] ?? 5;
    if ($sa !== $sb) return $sa <=> $sb;

    // prioridade: alta > média > baixa
    $pord = ['alta'=>3,'média'=>2,'baixa'=>1];
    $pa = $pord[mb_strtolower($a['prioridade'])] ?? 0;
    $pb = $pord[mb_strtolower($b['prioridade'])] ?? 0;
    if ($pa !== $pb) return $pb <=> $pa;

    // criado_em DESC
    return strtotime($b['criado_em']) <=> strtotime($a['criado_em']);
  });

  return $items;
}

/**
 * TODO: Substituir por SELECT real de contagem por status.
 * Retorne os totais para os cards.
 */
function contarPorStatus(): array {
  $totais = ['aberto'=>0,'em_andamento'=>0,'resolvido'=>0,'cancelado'=>0,'todos'=>0];
  foreach (MOCK_DATA as $m) {
    $totais['todos']++;
    if (isset($totais[$m['status']])) $totais[$m['status']]++;
  }
  return $totais;
}

/**
 * TODO: Implementar UPDATE real (linha).
 */
function atualizarStatusLinha(int $id, string $novoStatus): void {
  // Ex.: UPDATE chamados SET status = :s WHERE id = :id
  // (Aqui não fazemos nada: sem BD)
}

/**
 * TODO: Implementar UPDATE real (lote).
 */
function atualizarStatusLote(array $ids, string $novoStatus): void {
  // Ex.: UPDATE chamados SET status = :s WHERE id IN (...)
  // (Aqui não fazemos nada: sem BD)
}

// ======= LISTAGEM (sem BD; usa stubs acima) =======
$items  = listarChamados(['q'=>$busca,'status'=>$status,'prioridade'=>$priori]);
$totais = contarPorStatus();

// ======= AÇÕES POST (mantidas; sem executar UPDATE agora) =======
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  check_csrf();

  $acao = $_POST['acao'] ?? ''; // 'linha' ou 'lote'
  $novoStatus = $_POST['novo_status'] ?? '';
  $permitidos = ['aberto','em_andamento','resolvido','cancelado'];
  if (!in_array($novoStatus, $permitidos, true)) {
    header('Location: home-admin.php?msg=Status%20inválido'); exit();
  }

  if ($acao === 'linha') {
    $id = (int)($_POST['id'] ?? 0);
    // TODO: chamar atualizarStatusLinha($id, $novoStatus);
    // atualizarStatusLinha($id, $novoStatus);

  } elseif ($acao === 'lote') {
    $ids = array_filter(array_map('intval', $_POST['ids'] ?? []));
    if ($ids) {
      // TODO: chamar atualizarStatusLote($ids, $novoStatus);
      // atualizarStatusLote($ids, $novoStatus);
    }
  }

  // Redireciona mantendo filtros com mensagem (sem BD por enquanto)
  $qs = http_build_query([
    'q'=>$busca, 'status'=>$status, 'prioridade'=>$priori,
    'msg'=>"Previsto: atualizar status para '$novoStatus' (implemente o UPDATE no stub)"
  ]);
  header("Location: home-admin.php?$qs"); exit();
}

// ======= HELPERS UI =======
function badge(string $status): string {
  $map = [
    'aberto'        => ['#dbeafe','#1e40af','Aberto'],
    'em_andamento'  => ['#fef3c7','#92400e','Em andamento'],
    'resolvido'     => ['#dcfce7','#065f46','Resolvido'],
    'cancelado'     => ['#fee2e2','#991b1b','Cancelado'],
  ];
  [$bg,$fg,$tx] = $map[$status] ?? ['#e5e7eb','#374151',ucfirst($status)];
  return "<span style='background:$bg;color:$fg;padding:6px 10px;border-radius:999px;font-weight:700;font-size:12px'>$tx</span>";
}
function chip(string $txt, string $bg, string $fg='#111827'): string {
  return "<span style='background:$bg;color:$fg;padding:6px 10px;border-radius:999px;font-weight:700;font-size:12px'>$txt</span>";
}

// ======= Dados da UI =======
$usuario = $_SESSION['usuario_nome'] ?? 'Administrador';
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>HelpDesk — Admin</title>
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
  .grid{display:grid;grid-template-columns:repeat(4,minmax(160px,1fr));gap:12px}
  .kpi{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:16px;box-shadow:var(--shadow)}
  .kpi .v{font-size:22px;font-weight:800}
  .kpi .t{color:var(--muted);font-size:12px}
  .card{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);padding:16px}
  .filters{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:8px}
  .input,.select{padding:10px 12px;border:1px solid var(--border);border-radius:10px;font-size:14px;background:#fff}
  .btn{border:none;border-radius:10px;padding:10px 16px;font-weight:700;cursor:pointer}
  .btn.primary{background:var(--primary);color:#fff}
  .btn.primary:hover{background:var(--primary-700)}
  .btn.ok{background:var(--ok);color:#fff}
  .btn.warn{background:var(--warn);color:#111827}
  .btn.danger{background:var(--danger);color:#fff}
  .table{width:100%;border-collapse:separate;border-spacing:0;margin-top:6px}
  .table th,.table td{padding:12px 10px;border-bottom:1px solid var(--border);text-align:left;font-size:14px;vertical-align:middle}
  .table th{font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:.04em}
  .row-actions{display:flex;gap:6px}
  .msg{padding:10px 12px;border:1px solid var(--border);background:#ecfeff;color:#0369a1;border-radius:10px}
  .bulk{display:flex;gap:8px;align-items:center;flex-wrap:wrap;margin:10px 0}
  @media (max-width:1100px){.grid{grid-template-columns:repeat(2,1fr)}}
  @media (max-width:700px){.grid{grid-template-columns:1fr}}
  @media (max-width:900px){
    .table thead{display:none}
    .table tr{display:block;background:#fff;margin-bottom:12px;border:1px solid var(--border);border-radius:10px}
    .table td{display:flex;justify-content:space-between;border-bottom:0;padding:10px 14px}
    .table td::before{content:attr(data-label);font-weight:700;color:#374151}
  }
</style>
</head>
<body>
  <aside class="sidebar">
    <div class="brand">🛠️ HelpDesk — Admin</div>
    <nav>
      <ul class="nav">
        <li><a class="active" href="home-admin.php">📋 Painel</a></li>
        <li><a href="perfil-admin.php">👤 Perfil</a></li>
      </ul>
    </nav>
    <form action="../logout.php" method="post">
      <button class="logout" type="submit">Desconectar</button>
    </form>
  </aside>

  <main class="main">
    <div class="top">
      <h1>📋 Painel do Administrador</h1>
      <div class="muted">Olá, <?= htmlspecialchars($usuario) ?></div>
    </div>

    <!-- KPIs -->
    <section class="grid">
      <div class="kpi">
        <div class="v"><?= (int)$totais['aberto'] ?></div>
        <div class="t">Abertos</div>
      </div>
      <div class="kpi">
        <div class="v"><?= (int)$totais['em_andamento'] ?></div>
        <div class="t">Em andamento</div>
      </div>
      <div class="kpi">
        <div class="v"><?= (int)$totais['resolvido'] ?></div>
        <div class="t">Resolvidos</div>
      </div>
      <div class="kpi">
        <div class="v"><?= (int)$totais['todos'] ?></div>
        <div class="t">Total</div>
      </div>
    </section>

    <?php if ($msg): ?>
      <div class="msg"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <!-- Lista de chamados -->
    <section class="card">
      <form class="filters" method="get" action="home-admin.php">
        <input class="input" type="search" name="q" placeholder="Buscar por título, categoria ou e-mail..." value="<?= htmlspecialchars($busca) ?>" />
        <select class="select" name="status">
          <?php
            $opts = ['aberto'=>'Abertos','em_andamento'=>'Em andamento','resolvido'=>'Resolvidos','cancelado'=>'Cancelados','todos'=>'Todos'];
            foreach($opts as $k=>$v){
              $sel = $status===$k ? 'selected' : '';
              echo "<option value='$k' $sel>$v</option>";
            }
          ?>
        </select>
        <select class="select" name="prioridade">
          <?php
            $pOpts = ['todas'=>'Todas prioridades','alta'=>'Alta','média'=>'Média','baixa'=>'Baixa'];
            foreach($pOpts as $k=>$v){
              $sel = $priori===$k ? 'selected' : '';
              echo "<option value='$k' $sel>$v</option>";
            }
          ?>
        </select>
        <button class="btn primary" type="submit">Filtrar</button>
      </form>

      <!-- Ação em lote -->
      <form method="post" class="bulk" action="home-admin.php?<?= htmlspecialchars(http_build_query(['q'=>$busca,'status'=>$status,'prioridade'=>$priori])) ?>">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
        <input type="hidden" name="acao" value="lote">
        <label class="muted">Ação em lote:</label>
        <select class="select" name="novo_status" required>
          <option value="" selected disabled>Definir status…</option>
          <option value="em_andamento">Marcar como Em andamento</option>
          <option value="resolvido">Marcar como Resolvido</option>
          <option value="cancelado">Marcar como Cancelado</option>
          <option value="aberto">Reabrir (Aberto)</option>
        </select>
        <button class="btn ok" type="submit">Aplicar</button>

        <div class="table-wrap" style="width:100%">
          <table class="table">
            <thead>
              <tr>
                <th><input type="checkbox" id="chk-all" onclick="toggleAll(this)"></th>
                <th>#ID</th>
                <th>Título</th>
                <th>Categoria</th>
                <th>Prioridade</th>
                <th>Status</th>
                <th>Aberto por</th>
                <th>Criado em</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
            <?php if (empty($items)): ?>
              <tr><td colspan="9" class="muted" style="padding:16px">Nenhum chamado encontrado.</td></tr>
            <?php else: foreach ($items as $c): ?>
              <tr>
                <td data-label="Selecionar">
                  <input type="checkbox" name="ids[]" value="<?= (int)$c['id'] ?>">
                </td>
                <td data-label="#ID"><?= (int)$c['id'] ?></td>
                <td data-label="Título"><?= htmlspecialchars($c['titulo']) ?></td>
                <td data-label="Categoria"><?= chip(htmlspecialchars($c['categoria']), '#eef2ff','#3730a3') ?></td>
                <td data-label="Prioridade">
                  <?php
                    $pbg = ['alta'=>'#fee2e2','média'=>'#fef3c7','baixa'=>'#dcfce7'][mb_strtolower($c['prioridade'])] ?? '#e5e7eb';
                    $pfg = ['alta'=>'#991b1b','média'=>'#92400e','baixa'=>'#065f46'][mb_strtolower($c['prioridade'])] ?? '#374151';
                    echo chip(ucfirst($c['prioridade']), $pbg, $pfg);
                  ?>
                </td>
                <td data-label="Status"><?= badge($c['status']) ?></td>
                <td data-label="Aberto por"><?= htmlspecialchars($c['email_usuario']) ?></td>
                <td data-label="Criado em"><?= date('d/m/Y H:i', strtotime($c['criado_em'])) ?></td>
                <td data-label="Ações">
                  <form method="post" class="row-actions" action="home-admin.php?<?= htmlspecialchars(http_build_query(['q'=>$busca,'status'=>$status,'prioridade'=>$priori])) ?>">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
                    <input type="hidden" name="acao" value="linha">
                    <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                    <button class="btn warn"   name="novo_status" value="em_andamento" type="submit" title="Marcar como Em andamento">▶️</button>
                    <button class="btn ok"     name="novo_status" value="resolvido"     type="submit" title="Marcar como Resolvido">✅</button>
                    <button class="btn danger" name="novo_status" value="cancelado"     type="submit" title="Cancelar chamado">⛔</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
      </form>
    </section>
  </main>

  <script>
    function toggleAll(source){
      const boxes = document.querySelectorAll('input[name="ids[]"]');
      for(const b of boxes){ b.checked = source.checked; }
    }
  </script>
</body>
</html>
