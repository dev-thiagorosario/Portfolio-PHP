<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Taskly — Todas as Tarefas</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<style>
:root{
  --brand:#0a66ff; --brand-600:#0b57d0; --bg:#f6f7fb; --card:#ffffff;
  --text:#1f2937; --muted:#6b7280; --radius:14px; --shadow:0 10px 25px rgba(2,6,23,.08);
  --ring:0 0 0 3px rgba(10,102,255,.25);
  --space-2:10px; --space-3:14px; --space-4:18px; --space-5:24px;
}
@media (prefers-color-scheme: dark){
  :root{ --bg:#0b1220; --card:#0f172a; --text:#e5e7eb; --muted:#94a3b8; --shadow:0 10px 25px rgba(2,6,23,.45) }
}
*{box-sizing:border-box}
body{
  margin:0; font-family:Manrope,system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Arial,sans-serif;
  color:var(--text); background:
  radial-gradient(1200px 800px at 15% -10%, rgba(10,102,255,.08), transparent 60%),
  radial-gradient(900px 600px at 90% 0%, rgba(34,197,94,.07), transparent 60%), var(--bg);
  line-height:1.5; font-size:1rem;
}
a,button,input,select,textarea{outline:none}
a:focus-visible,button:focus-visible,input:focus-visible,select:focus-visible,textarea:focus-visible{box-shadow:var(--ring)}

.search-field { position: relative; }
.search-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}
.search-wrapper .input {
  width: 100%;
  padding-right: 42px; /* espaço pro botão */
}
.search-btn {
  position: absolute;
  right: 8px;
  border: none;
  background: linear-gradient(135deg, var(--brand), #5b8eff);
  color: #fff;
  border-radius: 8px;
  width: 34px;
  height: 34px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background .2s ease, transform .05s ease;
}
.search-btn:hover {
  background: var(--brand-600);
}
.search-btn:active {
  transform: translateY(1px);
}

header{
  position:sticky; top:0; z-index:10; backdrop-filter:saturate(180%) blur(10px);
  background:linear-gradient(180deg, rgba(15,23,42,.85), rgba(15,23,42,.7)); color:#fff;
  border-bottom:1px solid rgba(255,255,255,.08);
}
.nav{max-width:1000px; margin:auto; padding:14px 20px; display:flex; align-items:center; justify-content:space-between; gap:12px}
.brand{display:flex; align-items:center; gap:10px; font-weight:800}
.brand i{background:linear-gradient(135deg,var(--brand),#5b8eff); -webkit-background-clip:text; background-clip:text; color:transparent}
.nav a{color:#e5e7eb; text-decoration:none; padding:8px 12px; border-radius:10px; font-weight:600}
.nav a:hover{background:rgba(255,255,255,.08)}

main{max-width:820px; margin:24px auto; padding:0 20px}
.panel{background:var(--card); border-radius:var(--radius); box-shadow:var(--shadow); padding:var(--space-5)}
.panel h1{margin:0 0 var(--space-3); font-size:1.25rem; display:flex; gap:10px; align-items:center}

/* toolbar */
.toolbar{
  display:grid; 
  gap:var(--space-3); 
  grid-template-columns:180px 180px 1fr; /* busca agora é o maior */
  align-items:end; 
  margin-bottom:var(--space-4)
}
@media (max-width:760px){
  .toolbar{grid-template-columns:1fr}
}
.label{font-weight:800; color:var(--muted); font-size:.95rem}
.input,.select{
  width:100%; border:1px solid rgba(2,6,23,.12); background:var(--card); color:var(--text);
  border-radius:12px; padding:12px 14px; font:inherit
}
.btn{
  appearance:none; border:none; padding:12px 16px; border-radius:12px; font-weight:900;
  cursor:pointer; display:inline-flex; align-items:center; gap:10px; text-decoration:none
}
.btn-primary{background:linear-gradient(135deg,var(--brand),#5b8eff); color:#fff}

/* lista */
.list{list-style:none; padding:0; margin:0; display:grid; gap:var(--space-3)}
.item{
  background:linear-gradient(0deg, rgba(2,6,23,.03), rgba(2,6,23,.03)), var(--card);
  border:1px solid rgba(2,6,23,.08); border-left:6px solid var(--brand);
  border-radius:12px; padding:var(--space-4) var(--space-5);
  display:grid; grid-template-columns:1fr auto; gap:var(--space-3); align-items:start
}
.title{font-weight:800}
.meta{display:flex; gap:var(--space-3); color:var(--muted); flex-wrap:wrap; font-weight:700}
.pills{display:flex; gap:8px; flex-wrap:wrap}
.pill{padding:6px 10px; border-radius:999px; border:1px solid rgba(2,6,23,.12); font-weight:700; font-size:.82rem; color:var(--muted)}

/* paginação */
.pagination{display:flex; gap:8px; justify-content:center; margin-top:var(--space-4)}
.page{padding:10px 14px; border-radius:10px; border:1px solid rgba(2,6,23,.15); background:var(--card); text-decoration:none; color:var(--text); font-weight:800}
.page[aria-current="page"]{background:linear-gradient(135deg, var(--brand), #5b8eff); color:#fff}

footer{max-width:1000px; margin:24px auto 36px; padding:0 20px; color:var(--muted); text-align:center}
</style>
</head>
<body>
<header>
  <div class="nav">
    <div class="brand"><i class="fa-solid fa-square-check"></i><span>Taskly</span></div>
    <nav>
      <a href="index.php"><i class="fa-solid fa-house"></i> Início</a>
      <a href="all_tasks.php" aria-current="page"><i class="fa-solid fa-list-check"></i> Todas</a>
      <a href="new_task.php"><i class="fa-solid fa-plus"></i> Nova</a>
    </nav>
  </div>
</header>

<main>
  <section class="panel">
    <h1><i class="fa-solid fa-list-check"></i> Todas as tarefas</h1>

 <form class="toolbar" method="get" action="#" role="search" aria-label="Filtrar tarefas">
  <div>
    <label class="label" for="status">Status</label>
    <select class="select" id="status" name="status">
      <option value="">Todos</option>
      <option value="a_iniciar">A iniciar</option>
      <option value="andamento">Em andamento</option>
      <option value="concluida">Concluída</option>
      <option value="cancelada">Cancelada</option>
    </select>
  </div>

  <div>
    <label class="label" for="prioridade">Prioridade</label>
    <select class="select" id="prioridade" name="prioridade">
      <option value="">Todas</option>
      <option value="baixa">Baixa</option>
      <option value="media">Média</option>
      <option value="alta">Alta</option>
      <option value="critica">Urgente</option>
    </select>
  </div>

  <div class="search-field">
    <label class="label" for="q">Buscar</label>
    <div class="search-wrapper">
      <input class="input" id="q" name="q" type="search" placeholder="Título, tag, responsável…" />
      <button class="search-btn" type="submit" title="Buscar">
        <i class="fa-solid fa-magnifying-glass"></i>
      </button>
    </div>
  </div>


</form>
    </section>
</main>

<footer>
  <small>© Taskly. Interface demonstrativa.</small>
</footer>
</body>
</html>
