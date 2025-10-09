<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Taskly — Nova Tarefa</title>
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

.form{display:grid; gap:var(--space-4)}
.row{display:grid; grid-template-columns:1fr 1fr; gap:var(--space-3)}
@media (max-width:720px){.row{grid-template-columns:1fr}}

.label{font-weight:800; color:var(--muted); font-size:.95rem}
.input,.select,.textarea{
  width:100%; border:1px solid rgba(2,6,23,.12); background:var(--card); color:var(--text);
  border-radius:12px; padding:12px 14px; font:inherit
}
.textarea{min-height:110px; resize:vertical}

.actions{display:flex; gap:12px; flex-wrap:wrap; margin-top:2px}
.btn{
  appearance:none; border:none; padding:12px 16px; border-radius:12px; font-weight:900;
  cursor:pointer; display:inline-flex; align-items:center; gap:10px; text-decoration:none
}
.btn-primary{background:linear-gradient(135deg,var(--brand),#5b8eff); color:#fff}
.btn-ghost{background:transparent; color:var(--brand); border:2px solid var(--brand)}
footer{max-width:1000px; margin:24px auto 36px; padding:0 20px; color:var(--muted); text-align:center}
</style>
</head>
<body>
<header>
  <div class="nav">
    <div class="brand"><i class="fa-solid fa-square-check"></i><span>Taskly</span></div>
    <nav>
      <a href="index.php"><i class="fa-solid fa-house"></i> Início</a>
      <a href="all_tasks.php"><i class="fa-solid fa-list-check"></i> Todas</a>
      <a href="new_task.php" aria-current="page"><i class="fa-solid fa-plus"></i> Nova</a>
    </nav>
  </div>
</header>

<main>
  <section class="panel">
    <h1><i class="fa-solid fa-plus"></i> Nova tarefa</h1>

   
    <form class="form" method="post" name="tarefa" action="../core/task_controller.php" novalidate>
      <div class="row">
        <div>
          <label class="label" for="titulo">Título</label>
          <input class="input" id="titulo" name="titulo" type="text" placeholder="Ex.: Revisar PR #42" required>
        </div>
        <div>
          <label class="label" for="responsavel">Responsável (opcional)</label>
          <input class="input" id="responsavel" name="responsavel" type="text" placeholder="Ex.: Ana Silva">
        </div>
      </div>

      <div class="row">
        <div>
          <label class="label" for="data_limite">Data limite</label>
          <input class="input" id="data_limite" name="data_limite" type="date">
        </div>
        <div>
          <label class="label" for="urgencia">Urgência</label>
          <select class="select" id="urgencia" name="urgencia">
            <option value="baixa" selected>Baixa</option>
            <option value="media">Média</option>
            <option value="alta">Alta</option>
            <option value="critica">Urgente</option>
          </select>
        </div>
      </div>

      <div class="row">
        <div>
          <label class="label" for="status">Status inicial</label>
          <select class="select" id="status" name="status">
            <option value="a_iniciar" selected>A iniciar</option>
            <option value="andamento" disabled>Em andamento</option>
            <option value="concluida" disabled>Concluída</option>
            <option value="cancelada" disabled>Cancelada</option>
          </select>
        </div>
      </div>

      <div class="actions">
        <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Salvar</button>
        <a class="btn btn-ghost" href="all_tasks.php"><i class="fa-solid fa-list-check"></i> Ver tarefas</a>
        <a class="btn btn-ghost" href="index.php"><i class="fa-solid fa-house"></i> Início</a>
      </div>
    </form>
  </section>
</main>

<footer>
  <small>© Taskly. Interface demonstrativa.</small>
</footer>
</body>
</html>
