<!DOCTYPE html>
<!-- HTML 100% CRIADO POR IA -->
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>HelpDesk - Entrar</title>
  <style>
    :root{
      --bg: linear-gradient(135deg, #e2e8f0, #f8fafc);
      --card:#f9fafb;
      --text:#1f2937;
      --muted:#6b7280;
      --primary:#4f6fa8;
      --primary-hover:#3d5c92;
      --radius:16px;
      --shadow:0 8px 30px rgba(0,0,0,.1);
    }

    *{ box-sizing: border-box; margin:0; padding:0; }
    html, body { height: 100%; }
    body{
      font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
      background: var(--bg);
      color: var(--text);
      display:flex;
      align-items:center;
      justify-content:center;
      padding:20px;
    }

    .card{
      width: min(94vw, 440px);
      background: var(--card);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 32px;
      transition: transform .2s ease, box-shadow .2s ease;
    }
    .card:hover{
      transform: translateY(-2px);
      box-shadow: 0 10px 40px rgba(0,0,0,.15);
    }

    header{
      text-align:center;
      margin-bottom: 20px;
    }

    header a{
      text-decoration:none;
      font-size:1.4rem;
      font-weight:700;
      color: var(--primary);
      transition: color .2s;
    }
    header a:hover{ color: var(--primary-hover); }

    .title{
      text-align:center;
      font-weight: 600;
      font-size: 1.2rem;
      margin: 10px 0 24px;
      color: var(--text);
    }

    .field{
      margin: 14px 0;
      display:flex;
      flex-direction:column;
      gap:8px;
    }

    label{
      font-size:.9rem;
      color: var(--muted);
    }

    input{
      width:100%;
      padding: 14px 12px;
      border: 1px solid #d1d5db;
      border-radius: 10px;
      background:#fff;
      font-size:1rem;
      outline: none;
      transition: border-color .2s, box-shadow .2s;
    }
    input:focus{
      border-color:#4f6fa8;
      box-shadow: 0 0 0 3px rgba(79, 111, 168, .2);
    }

    .btn{
      width:100%;
      margin-top: 18px;
      padding: 14px 12px;
      border:0;
      border-radius: 10px;
      background: var(--primary);
      color:#fff;
      font-weight:600;
      font-size:1rem;
      cursor:pointer;
      transition: background .2s, transform .02s;
    }
    .btn:hover{ background: var(--primary-hover); }
    .btn:active{ transform: translateY(1px); }

    .link{
      display:block;
      text-align:center;
      margin-top: 18px;
      color:#3168e0;
      text-decoration: none;
      font-size:.95rem;
      font-weight:500;
    }
    .link:hover{ text-decoration: underline; }

    .field label{ cursor: pointer; }
  </style>
</head>
<body>
    <!-- HTML CRIADO POR IA -->
 <main class="card" role="main" aria-labelledby="titulo">
    <?php if(isset($_GET['erro']) && $_GET['erro'] === '1'): ?>
        <div style="color: red; text-align: center; margin: 10px 0;">
            Email ou senha incorretos!
        </div>
    <?php endif; ?>
    <header>
      <a href="index.html">📌 HelpDesk</a>
    </header>
    <h1 id="titulo" class="title">Entrar na conta</h1>


    <form action="/repository/auth_user.php" method="post" novalidate>
      <div class="field">
        <label for="email">E-mail</label>
        <input
          type="email"
          id="email"
          name="email"
          placeholder="seu@email.com"
          autocomplete="email"
          required
        />
      </div>

      <div class="field">
        <label for="senha">Senha</label>
        <input
          type="password"
          id="senha"
          name="senha"
          placeholder="Sua senha"
          autocomplete="current-password"
          minlength="6"
          required
        />
      </div>

      <button class="btn" type="submit">Entrar na conta</button>
      <a class="link" href="registrar.php">Criar uma conta</a>
    </form>
  </main>


</body>
</html>
