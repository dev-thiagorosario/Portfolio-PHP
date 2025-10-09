<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Taskly — Início</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<style>
:root{
  --brand:#0a66ff; --brand-600:#0b57d0; --accent:#22c55e; --danger:#ef4444; --warning:#f59e0b; --slate:#64748b;
  --bg:#f6f7fb; --card:#ffffff; --text:#1f2937; --muted:#6b7280;
  --radius:14px; --shadow:0 10px 25px rgba(2,6,23,.08);
  --ring:0 0 0 3px rgba(10,102,255,.25);
  --s2:10px; --s3:14px; --s4:18px; --s5:24px; --s6:32px;
}
@media (prefers-color-scheme: dark){
  :root{ --bg:#0b1220; --card:#0f172a; --text:#e5e7eb; --muted:#94a3b8; --shadow:0 10px 25px rgba(2,6,23,.45) }
}
*{box-sizing:border-box}
html,body{height:100%}
body{
  margin:0; font-family:Manrope,system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Arial,sans-serif; color:var(--text);
  background:
    radial-gradient(1200px 800px at 15% -10%, rgba(10,102,255,.08), transparent 60%),
    radial-gradient(900px 600px at 90% 0%, rgba(34,197,94,.07), transparent 60%), var(--bg);
  line-height:1.5; font-size:1rem;
}
a,button{outline:none} a:focus-visible,button:focus-visible{box-shadow:var(--ring)}

header{
  position:sticky; top:0; z-index:10; backdrop-filter:saturate(180%) blur(10px);
  background:linear-gradient(180deg, rgba(15,23,42,.85), rgba(15,23,42,.7));
  color:#fff; border-bottom:1px solid rgba(255,255,255,.08);
}
.nav{max-width:1100px; margin:auto; padding:14px 20px; display:flex; align-items:center; justify-content:space-between; gap:12px}
.brand{display:flex; align-items:center; gap:10px; font-weight:800; white-space:nowrap}
.brand i{background:linear-gradient(135deg,var(--brand),#5b8eff); -webkit-background-clip:text; background-clip:text; color:transparent; font-size:1.3rem}
.nav a{color:#e5e7eb; text-decoration:none; padding:8px 12px; border-radius:10px; font-weight:600; display:inline-flex; align-items:center; gap:8px}
.nav a:hover{background:rgba(255,255,255,.08)}
.nav .logout{border:1px solid rgba(255,255,255,.18)}

main {
  max-width: 1100px;
  margin: var(--s6) auto;
  padding: 0 20px;
  display: grid;
  grid-template-columns: 1.8fr 340px; /* aside mais estreito */
  gap: var(--s6);
}
@media (max-width:980px){main{grid-template-columns:1fr}}

.panel{background:var(--card); border-radius:var(--radius); box-shadow:var(--shadow); padding:var(--s5)}
.panel h2{margin:0 0 var(--s3); font-size:1.15rem; display:flex; align-items:center; gap:10px}

.tabs{display:flex; gap:8px; margin-bottom:var(--s3); flex-wrap:wrap}
.tab{padding:8px 14px; border-radius:999px; background:#e5e7eb; color:#111827; font-weight:800; border:0; cursor:default}
.tab.active{background:var(--brand); color:#fff}

.list{list-style:none; margin:0; padding:0; display:grid; gap:var(--s3)}
.item{
  background:linear-gradient(0deg, rgba(2,6,23,.03), rgba(2,6,23,.03)), var(--card);
  border:1px solid rgba(2,6,23,.08); border-left:6px solid var(--brand);
  border-radius:12px; padding:var(--s4) var(--s5);
  display:grid; grid-template-columns:1fr auto; gap:var(--s3); align-items:start
}
.title{font-weight:800}
.meta{display:flex; align-items:center; gap:var(--s3); color:var(--muted); font-weight:700; flex-wrap:wrap}

.badge{padding:6px 10px; border-radius:999px; font-size:.82rem; font-weight:900; line-height:1; border:1px solid transparent}
.badge.a-iniciar{background:rgba(100,116,139,.12); color:var(--slate); border-color:rgba(100,116,139,.25)}
.badge.andamento{background:rgba(10,102,255,.10); color:var(--brand-600); border-color:rgba(10,102,255,.25)}
.badge.concluida{background:rgba(34,197,94,.1); color:#22c55e; border-color:rgba(34,197,94,.25)}
.badge.cancelada{background:rgba(239,68,68,.1); color:#ef4444; border-color:rgba(239,68,68,.25)}

.duedate.urgent{color:var(--danger); font-weight:900}
.duedate.soon{color:var(--warning); font-weight:900}

/* ações bem discretas */
.actions{display:flex; gap:8px; flex-wrap:wrap; justify-content:flex-end}
.action-btn{
  border:1px solid rgba(2,6,23,.12); background:var(--card); color:var(--text);
  padding:8px 12px; border-radius:10px; font-weight:800; display:inline-flex; align-items:center; gap:8px;
  text-decoration:none; cursor:pointer; transition: border-color .2s ease, transform .05s ease;
}
.action-btn:hover{border-color:rgba(2,6,23,.28)}
.action-btn:active{transform:translateY(1px)}
.action-btn.play{border-color:rgba(10,102,255,.28)}
.action-btn.check{border-color:rgba(34,197,94,.3)}
.action-btn.x{border-color:rgba(239,68,68,.3)}
.action-btn[aria-disabled="true"]{opacity:.6; cursor:not-allowed}

/* progresso compacto */
.progress{display:flex; gap:6px; flex-wrap:wrap; margin-top:6px}
.step{padding:4px 8px; border-radius:999px; border:1px dashed rgba(2,6,23,.18); color:var(--muted); font-weight:800; font-size:.72rem}
.step.active{border-style:solid; border-color:rgba(10,102,255,.35); color:var(--brand-600)}
.step.done{border-style:solid; border-color:rgba(34,197,94,.35); color:#22c55e}

/* CTA lateral */
.cta{display:grid; gap:var(--s3)}
.btn{appearance:none; border:none; padding:12px 16px; border-radius:12px; font-weight:900; cursor:pointer; display:inline-flex; align-items:center; gap:10px; justify-content:center; text-decoration:none}
.btn-primary{background:linear-gradient(135deg, var(--brand), #5b8eff); color:#fff}
.btn-outline{background:transparent; color:var(--brand); border:2px solid var(--brand)}

footer{max-width:1100px; margin:var(--s6) auto var(--s6); padding:0 20px; color:var(--muted); text-align:center}
.empty{padding:var(--s4); border:1px dashed rgba(2,6,23,.15); border-radius:12px; color:var(--muted); display:flex; gap:8px; align-items:center}
.tip{display:flex; gap:8px; align-items:center; color:var(--muted); margin-top:8px; font-size:.9rem}

.cta {
  width: 100%;
  max-width: 340px; /* largura máxima do aside */
  margin-left: auto;
  margin-right: 0;
  justify-self: end; /* alinha à direita no grid */
  box-sizing: border-box;
}
</style>
</head>
<body>
<header role="banner">
  <div class="nav">
    <div class="brand" aria-label="Taskly">
      <i class="fa-solid fa-square-check" aria-hidden="true"></i><span>Taskly • Gerenciador de Tarefas</span>
    </div>
    <nav aria-label="Navegação principal">
      <a href="index.php" aria-current="page"><i class="fa-solid fa-house"></i> <span>Início</span></a>
      <a href="all_tasks.php"><i class="fa-solid fa-list-check"></i> <span>Todas as Tarefas</span></a>
      <a href="new_task.php"><i class="fa-solid fa-plus"></i> <span>Nova Tarefa</span></a>
      <a class="logout" href="#"><i class="fa-solid fa-right-from-bracket"></i> <span>Sair</span></a>
    </nav>
  </div>
</header>

<main>
  <section class="panel" aria-labelledby="sec-destaque">
    <h2 id="sec-destaque"><i class="fa-solid fa-bolt"></i> Em destaque</h2>

    <div class="tabs" aria-label="Abas (apenas visuais)">
      <button class="tab active" type="button" aria-pressed="true">Pendentes</button>
      <button class="tab" type="button" aria-pressed="false" disabled title="UI demonstrativa">Todas</button>
    </div>

    <ul class="list" aria-label="Tarefas em destaque">
     
  </section>

  <aside class="panel cta" aria-label="Ações rápidas">
    <h2><i class="fa-solid fa-rocket"></i> Ações rápidas</h2>
    <a class="btn btn-primary" href="new_task.php"><i class="fa-solid fa-plus"></i> Criar nova tarefa</a>
    <a class="btn btn-outline" href="all_tasks.php"><i class="fa-solid fa-list"></i> Ver todas as tarefas</a>
  </aside>
</main>

<footer role="contentinfo">
  <small>© Taskly. Interface demonstrativa.</small>
</footer>
</body>
</html>
