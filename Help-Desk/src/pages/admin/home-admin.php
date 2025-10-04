<?php

declare(strict_types=1);

require_once __DIR__ . '/../../repository/authenticatedUser.php';

$authUser = getAuthenticatedUser();
$usuario = $authUser['user_name'] ?? ($_SESSION['usuario_nome'] ?? 'Administrador');

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}

function check_csrf(): void
{
    if (($_POST['csrf'] ?? '') !== ($_SESSION['csrf'] ?? '')) {
        http_response_code(403);
        exit('CSRF inválido.');
    }
}



function obterConexao(): ?PDO
{
    static $cached = false;
    static $pdo = null;

    if ($cached) {
        return $pdo;
    }

    $cached = true;

    $dsn = getenv('DB_DSN') ?: 'pgsql:host=127.0.0.1;port=5432;dbname=helpDesk';
    $user = getenv('DB_USER') ?: 'postgres';
    $pass = getenv('DB_PASS') ?: 'root';

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (Throwable $e) {
        error_log('obterConexao: ' . $e->getMessage());
        $pdo = null;
    }

    return $pdo;
}


function lerChamadosDosArquivos(): array
{
    $diretorio = __DIR__ . '/../../database/Chamados-database';
    if (!is_dir($diretorio)) {
        return [];
    }

    $arquivos = glob($diretorio . '/*.txt');
    if ($arquivos === false || count($arquivos) === 0) {
        return [];
    }

    sort($arquivos);

    $resultado = [];
    $sequencial = 1;

    foreach ($arquivos as $arquivo) {
        $linhas = file($arquivo, FILE_IGNORE_NEW_LINES);
        if ($linhas === false) {
            continue;
        }

        $titulo = '';
        $categoria = '';
        $descricao = [];
        $registradoEm = null;
        $coletandoDescricao = false;

        foreach ($linhas as $linha) {
            $linha = rtrim($linha);
            $partes = explode(':', $linha, 2);

            if (count($partes) === 2) {
                $chave = trim($partes[0]);
                $valor = trim($partes[1]);

                switch ($chave) {
                    case 'Título':
                        $titulo = $valor;
                        $coletandoDescricao = false;
                        continue 2;
                    case 'Categoria':
                        $categoria = $valor;
                        $coletandoDescricao = false;
                        continue 2;
                    case 'Descrição':
                        $descricao = [];
                        $coletandoDescricao = true;
                        if ($valor !== '') {
                            $descricao[] = $valor;
                        }
                        continue 2;
                    case 'Registrado em':
                        $registradoEm = $valor;
                        $coletandoDescricao = false;
                        continue 2;
                }
            }

            if ($coletandoDescricao) {
                $descricao[] = $linha;
            }
        }

        $titulo = $titulo !== '' ? $titulo : basename($arquivo, '.txt');
        $categoria = $categoria !== '' ? $categoria : 'Geral';

        $criadoEm = null;
        if ($registradoEm) {
            $dt = DateTime::createFromFormat(DateTime::ATOM, $registradoEm);
            if ($dt !== false) {
                $criadoEm = $dt->format('Y-m-d H:i:s');
            }
        }
        if ($criadoEm === null) {
            $criadoEm = date('Y-m-d H:i:s', filemtime($arquivo));
        }

        $resultado[] = [
            'id' => $sequencial++,
            'titulo' => $titulo,
            'categoria' => $categoria,
            'descricao' => implode("\n", $descricao),
            'status' => 'aberto',
            'prioridade' => 'média',
            'email_usuario' => 'desconhecido@local',
            'criado_em' => $criadoEm,
        ];
    }

    return $resultado;
}

function carregarChamados(?PDO $pdo): array
{
    if ($pdo === null) {
        return lerChamadosDosArquivos();
    }

    try {
        $sql = 'SELECT id, titulo, categoria, status, prioridade, email_usuario, criado_em FROM chamados ORDER BY criado_em DESC';
        $stmt = $pdo->query($sql);
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $dados ?: [];
    } catch (Throwable $e) {
        return lerChamadosDosArquivos();
    }
}

function filtrarChamados(array $chamados, string $busca, string $status, string $prioridade): array
{
    $buscaNormalizada = mb_strtolower($busca);
    $statusFiltro = $status;
    $prioridadeFiltro = $prioridade;

    $filtrados = array_filter($chamados, static function (array $chamado) use ($buscaNormalizada, $statusFiltro, $prioridadeFiltro) {
        $statusOk = $statusFiltro === 'todos' || $chamado['status'] === $statusFiltro;

        $prioridadeNormalizada = mb_strtolower((string)($chamado['prioridade'] ?? ''));
        $prioridadeOk = $prioridadeFiltro === 'todas' || $prioridadeNormalizada === mb_strtolower($prioridadeFiltro);

        $textoBusca = mb_strtolower(($chamado['titulo'] ?? '') . ' ' . ($chamado['categoria'] ?? '') . ' ' . ($chamado['email_usuario'] ?? ''));
        $buscaOk = $buscaNormalizada === '' || str_contains($textoBusca, $buscaNormalizada);

        return $statusOk && $prioridadeOk && $buscaOk;
    });

    $filtrados = array_values($filtrados);

    usort($filtrados, static function (array $a, array $b): int {
        return strcmp($b['criado_em'] ?? '', $a['criado_em'] ?? '');
    });

    return $filtrados;
}

function contarTotais(array $chamados): array
{
    $totais = [
        'aberto' => 0,
        'em_andamento' => 0,
        'resolvido' => 0,
        'cancelado' => 0,
        'todos' => count($chamados),
    ];

    foreach ($chamados as $chamado) {
        $status = $chamado['status'] ?? 'aberto';
        if (!isset($totais[$status])) {
            $totais[$status] = 0;
        }
        $totais[$status]++;
    }

    return $totais;
}

function chip(string $texto, string $bg, string $fg): string
{
    $label = htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
    return "<span style=\"background:$bg;color:$fg;padding:6px 10px;border-radius:999px;font-weight:700;font-size:12px\">$label</span>";
}

function badge(string $status): string
{
    $map = [
        'aberto' => ['#dbeafe', '#1e40af', 'Aberto'],
        'em_andamento' => ['#fef3c7', '#92400e', 'Em andamento'],
        'resolvido' => ['#dcfce7', '#065f46', 'Resolvido'],
        'cancelado' => ['#fee2e2', '#991b1b', 'Cancelado'],
    ];

    [$bg, $fg, $texto] = $map[$status] ?? ['#e5e7eb', '#374151', ucfirst($status)];
    return "<span style=\"background:$bg;color:$fg;padding:6px 10px;border-radius:999px;font-weight:700;font-size:12px\">$texto</span>";
}

function formatarData(?string $valor): string
{
    if (empty($valor)) {
        return '-';
    }

    try {
        $dt = new DateTime($valor);
        return $dt->format('d/m/Y H:i');
    } catch (Throwable $e) {
        return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
    }
}

function atualizarStatus(PDO $pdo, array $ids, string $novoStatus): int
{
    $idsFiltrados = array_values(array_unique(array_filter($ids, static fn ($id) => ctype_digit((string)$id))));
    if (empty($idsFiltrados)) {
        return 0;
    }

    $placeholders = implode(',', array_fill(0, count($idsFiltrados), '?'));
    $sql = "UPDATE chamados SET status = ? WHERE id IN ($placeholders)";

    $stmt = $pdo->prepare($sql);
    $params = array_merge([$novoStatus], $idsFiltrados);
    $stmt->execute($params);

    return $stmt->rowCount();
}

$busca = trim($_GET['q'] ?? '');
$status = $_GET['status'] ?? 'aberto';
$priori = $_GET['prioridade'] ?? 'todas';
$msg = '';

$pdo = obterConexao();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    $acao = $_POST['acao'] ?? '';
    $novoStatus = $_POST['novo_status'] ?? '';
    $statusValidos = ['aberto', 'em_andamento', 'resolvido', 'cancelado'];

    if (!in_array($novoStatus, $statusValidos, true)) {
        $msg = 'Status inválido.';
    } elseif ($pdo === null) {
        $msg = 'Operação indisponível no modo offline.';
    } else {
        try {
            if ($acao === 'lote') {
                $ids = $_POST['ids'] ?? [];
                $afetados = atualizarStatus($pdo, is_array($ids) ? $ids : [], $novoStatus);
                $msg = $afetados > 0 ? 'Status atualizado para os itens selecionados.' : 'Nenhum chamado foi atualizado.';
            } elseif ($acao === 'linha') {
                $id = $_POST['id'] ?? '';
                $afetados = atualizarStatus($pdo, [$id], $novoStatus);
                $msg = $afetados > 0 ? 'Status do chamado atualizado.' : 'Não foi possível atualizar o chamado informado.';
            } else {
                $msg = 'Ação desconhecida.';
            }
        } catch (Throwable $e) {
            $msg = 'Erro ao atualizar chamados: ' . $e->getMessage();
        }
    }
}

$dados = carregarChamados($pdo);
$totais = contarTotais($dados);
$items = filtrarChamados($dados, $busca, $status, $priori);

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
      <div class="muted">Olá, <?= htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8') ?></div>
    </div>

    <section class="grid">
      <div class="kpi">
        <div class="v"><?= (int)($totais['aberto'] ?? 0) ?></div>
        <div class="t">Abertos</div>
      </div>
      <div class="kpi">
        <div class="v"><?= (int)($totais['em_andamento'] ?? 0) ?></div>
        <div class="t">Em andamento</div>
      </div>
      <div class="kpi">
        <div class="v"><?= (int)($totais['resolvido'] ?? 0) ?></div>
        <div class="t">Resolvidos</div>
      </div>
      <div class="kpi">
        <div class="v"><?= (int)($totais['todos'] ?? 0) ?></div>
        <div class="t">Total</div>
      </div>
    </section>

    <?php if ($msg !== ''): ?>
      <div class="msg"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <section class="card">
      <form class="filters" method="get" action="home-admin.php">
        <input class="input" type="search" name="q" placeholder="Buscar por título, categoria ou e-mail..." value="<?= htmlspecialchars($busca, ENT_QUOTES, 'UTF-8') ?>" />
        <select class="select" name="status">
          <?php
            $opts = ['aberto' => 'Abertos', 'em_andamento' => 'Em andamento', 'resolvido' => 'Resolvidos', 'cancelado' => 'Cancelados', 'todos' => 'Todos'];
            foreach ($opts as $k => $v) {
                $sel = $status === $k ? 'selected' : '';
                echo "<option value=\"$k\" $sel>$v</option>";
            }
          ?>
        </select>
        <select class="select" name="prioridade">
          <?php
            $pOpts = ['todas' => 'Todas prioridades', 'alta' => 'Alta', 'média' => 'Média', 'baixa' => 'Baixa'];
            foreach ($pOpts as $k => $v) {
                $sel = $priori === $k ? 'selected' : '';
                echo "<option value=\"$k\" $sel>$v</option>";
            }
          ?>
        </select>
        <button class="btn primary" type="submit">Filtrar</button>
      </form>

      <form method="post" class="bulk" action="home-admin.php?<?= htmlspecialchars(http_build_query(['q' => $busca, 'status' => $status, 'prioridade' => $priori]), ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf'], ENT_QUOTES, 'UTF-8') ?>">
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
                  <input type="checkbox" name="ids[]" value="<?= htmlspecialchars((string)$c['id'], ENT_QUOTES, 'UTF-8') ?>">
                </td>
                <td data-label="#ID"><?= htmlspecialchars((string)$c['id'], ENT_QUOTES, 'UTF-8') ?></td>
                <td data-label="Título"><?= htmlspecialchars((string)$c['titulo'], ENT_QUOTES, 'UTF-8') ?></td>
                <td data-label="Categoria"><?= chip((string)$c['categoria'], '#eef2ff','#3730a3') ?></td>
                <td data-label="Prioridade">
                  <?php
                    $prioridade = mb_strtolower((string)($c['prioridade'] ?? ''));
                    $pbg = ['alta'=>'#fee2e2','média'=>'#fef3c7','baixa'=>'#dcfce7'][$prioridade] ?? '#e5e7eb';
                    $pfg = ['alta'=>'#991b1b','média'=>'#92400e','baixa'=>'#065f46'][$prioridade] ?? '#374151';
                    echo chip(ucfirst($c['prioridade'] ?? 'Indefinida'), $pbg, $pfg);
                  ?>
                </td>
                <td data-label="Status"><?= badge((string)$c['status']) ?></td>
                <td data-label="Aberto por"><?= htmlspecialchars((string)($c['email_usuario'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
                <td data-label="Criado em"><?= formatarData($c['criado_em'] ?? null) ?></td>
                <td data-label="Ações">
                  <form method="post" class="row-actions" action="home-admin.php?<?= htmlspecialchars(http_build_query(['q' => $busca, 'status' => $status, 'prioridade' => $priori]), ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf'], ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="acao" value="linha">
                    <input type="hidden" name="id" value="<?= htmlspecialchars((string)$c['id'], ENT_QUOTES, 'UTF-8') ?>">
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
