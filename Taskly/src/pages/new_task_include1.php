<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Taskly — Confirmação</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<style>
:root{
  --brand:#0a66ff; --brand-600:#0b57d0; --accent:#22c55e; --bg:#f6f7fb; --card:#ffffff;
  --text:#1f2937; --muted:#6b7280; --radius:14px; --shadow:0 10px 25px rgba(2,6,23,.08);
  --ring:0 0 0 3px rgba(10,102,255,.25);
  --s2:10px; --s3:14px; --s4:18px; --s5:24px; --s6:32px;
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
a,button{outline:none} a:focus-visible,button:focus-visible{box-shadow:var(--ring)}

header{
  position:sticky; top:0; z-index:10; backdrop-filter:saturate(180%) blur(10px);
  background:linear-gradient(180deg, rgba(15,23,42,.85), rgba(15,23,42,.7));
  color:#fff; border-bottom:1px solid rgba(255,255,255,.08);
}
.nav{max-width:1000px; margin:auto; padding:14px 20px; display:flex; align-items:center; justify-content:space-between; gap:12px}
.brand{display:flex; align-items:center; gap:10px; font-weight:800}
.brand i{background:linear-gradient(135deg,var(--brand),#5b8eff); -webkit-background-clip:text; background-clip:text; color:transparent}
.nav a{color:#e5e7eb; text-decoration:none; padding:8px 12px; border-radius:10px; font-weight:600}
.nav a:hover{background:rgba(255,255,255,.08)}

main{max-width:760px; margin:var(--s6) auto; padding:0 20px}
.panel{background:var(--card); border-radius:var(--radius); box-shadow:var(--shadow); padding:var(--s6); text-align:center}
.icon-wrap{
  width:64px; height:64px; border-radius:16px; margin:0 auto var(--s4);
  display:flex; align-items:center; justify-content:center;
  background:rgba(34,197,94,.12); color:var(--accent);
}
h1{margin:0 0 var(--s3); font-size:1.35rem; display:flex; align-items:center; justify-content:center; gap:10px}
.msg{color:var(--muted); margin:0 0 var(--s5)}
.detail{font-weight:800; color:#0b1220}
@media (prefers-color-scheme: dark){ .detail{color:#e5e7eb} }

.actions{display:flex; gap:12px; flex-wrap:wrap; justify-content:center}
.btn{appearance:none; border:none; padding:12px 16px; border-radius:12px; font-weight:900; cursor:pointer; display:inline-flex; align-items:center; gap:10px; text-decoration:none}
.btn-primary{background:linear-gradient(135deg,var(--brand),#5b8eff); color:#fff}
.btn-ghost{background:transparent; color:var(--brand); border:2px solid var(--brand)}

.alert{margin-top:var(--s4); display:inline-flex; align-items:center; gap:8px; color:var(--muted); font-size:.95rem}
[role="status"]{display:block; margin-top:var(--s3)}
footer{max-width:1000px; margin:var(--s6) auto var(--s6); padding:0 20px; color:var(--muted); text-align:center}
</style>
</head>
<body>
<header>
  <div class="nav">
    <div class="brand"><i class="fa-solid fa-square-check"></i><span>Taskly</span></div>
    <nav>
      <a href="index.php"><i class="fa-solid fa-house"></i> Início</a>
      <a href="all_tasks.php"><i class="fa-solid fa-list-check"></i> Todas</a>
      <a href="new_task.php"><i class="fa-solid fa-plus"></i> Nova</a>
    </nav>
  </div>
</header>

<main>
  <section class="panel" aria-labelledby="titulo">
    <div class="icon-wrap" aria-hidden="true">
      <i class="fa-solid fa-check fa-lg"></i>
    </div>

    <h1 id="titulo">Tarefa cadastrada!</h1>

    <p class="msg" role="status" aria-live="polite">
      <?php if ($titulo): ?>
        A tarefa <span class="detail">“<?php echo $titulo; ?>”</span> foi criada com sucesso.
      <?php else: ?>
        Sua tarefa foi criada com sucesso.
      <?php endif; ?>
    </p>

    <div class="actions">
      <a class="btn btn-primary" href="all_tasks.php"><i class="fa-solid fa-list-check"></i> Ver tarefas</a>
      <a class="btn btn-ghost" href="new_task.php"><i class="fa-solid fa-plus"></i> Cadastrar outra</a>
      <a class="btn btn-ghost" href="index.php"><i class="fa-solid fa-house"></i> Início</a>
    </div>

    <div class="alert">
      <i class="fa-regular fa-circle-check"></i>
      Você pode editar o status para <strong>A iniciar</strong>, <strong>Em andamento</strong> ou <strong>Concluída</strong> na listagem.
    </div>
  </section>
</main>

<footer>
  <small>© Taskly. Interface demonstrativa.</small>
</footer>
</body>
</html>
