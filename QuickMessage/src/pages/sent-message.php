<?php
declare(strict_types=1);

session_start();

$lastMessage = $_SESSION['last_message_meta'] ?? null;
$recipient = '';
$subject = '';
$sentAt = null;

if ($lastMessage !== null && is_array($lastMessage)) {
    $recipient = trim((string)($lastMessage['para'] ?? ''));
    $subject = trim((string)($lastMessage['assunto'] ?? ''));
    $sentAt = isset($lastMessage['enviado_em']) ? (int)$lastMessage['enviado_em'] : null;
    unset($_SESSION['last_message_meta']);
}

if (isset($_SESSION['flash_success'])) {
    unset($_SESSION['flash_success']);
}

$recipientDisplay = $recipient !== '' ? htmlspecialchars($recipient, ENT_QUOTES, 'UTF-8') : '—';
$subjectDisplay = $subject !== '' ? htmlspecialchars($subject, ENT_QUOTES, 'UTF-8') : '—';
$sentAtDisplay = $sentAt !== null ? htmlspecialchars(date('d/m/Y H:i', $sentAt), ENT_QUOTES, 'UTF-8') : '—';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>QuickMessage — Mensagem Enviada</title>
  <style>
    :root{
      --background:#0b0c0f; --foreground:#e9eef8;
      --primary:#5b8cff; --primary-foreground:#fff;
      --muted:#13151a; --muted-foreground:#a7b0c0;
      --accent:#141b24; --card:#0f1116; --border:#20232b;
      --ring:rgba(91,140,255,.35); --green:#22c55e; --orange:#f59e0b;
      --shadow:0 10px 30px rgba(0,0,0,.35); --radius:16px;
    }
    *{box-sizing:border-box} html,body{height:100%}
    body{
      margin:0;
      font-family:Inter,ui-sans-serif,system-ui,Segoe UI,Roboto,Helvetica,Arial;
      background:var(--background); color:var(--foreground); line-height:1.45;
    }
    .container{max-width:1120px;margin-inline:auto}
    .px-6{padding-inline:1.5rem}.py-4{padding-block:1rem}.py-8{padding-block:2rem}
    .mt-16{margin-top:4rem}.mb-2{margin-bottom:.5rem}.text-sm{font-size:.9rem}
    .text-2xl{font-size:1.5rem}.text-3xl{font-size:2rem}
    .font-bold{font-weight:700}.font-semibold{font-weight:600}
    .row{display:flex;align-items:center;gap:.5rem}.gap-2{gap:.5rem}.gap-3{gap:.75rem}.gap-4{gap:1rem}
    .shadow-sm{box-shadow:0 4px 16px rgba(0,0,0,.25)} .shadow-lg{box-shadow:var(--shadow)}
    .w-full{width:100%}

    /* Header */
    .header{background:var(--primary);color:var(--primary-foreground)}
    .badge{background:rgba(255,255,255,.12);padding:.25rem .75rem;border-radius:999px;font-size:.85rem}
    .logo-badge{background:rgba(255,255,255,.2);padding:.5rem;border-radius:12px}

    /* Card */
    .card{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden}
    .card__content{padding:2rem}

    /* Botões */
    .btn{
      display:inline-flex;align-items:center;justify-content:center;gap:.5rem;
      height:3rem;padding:0 1rem;font-weight:700;border-radius:12px;border:1px solid transparent;
      cursor:pointer;transition:transform .06s, filter .15s, background .2s, border .2s;
      text-decoration:none; color:inherit;
    }
    .btn:active{transform:translateY(1px)}
    .btn--primary{background:var(--primary);color:var(--primary-foreground)}
    .btn--outline{background:transparent;color:var(--foreground);border-color:var(--border)}
    .btn--block{width:100%}
    .btn-group{display:flex;flex-wrap:wrap;gap:.75rem;justify-content:center}

    /* Footer */
    .footer{background:var(--muted);padding:2rem 0;border-top:1px solid var(--border)}
    .muted{color:var(--muted-foreground)}

    /* Ícones */
    .ic{width:1.1rem;height:1.1rem}
    .ic-lg{width:1.25rem;height:1.25rem}
    .ic-xxl{width:3.5rem;height:3.5rem}
    .ic-primary{color:var(--primary)} .ic-green{color:var(--green)}

    /* Layout da seção central */
    .center-wrap{
      min-height: calc(100dvh - 160px);
      display:flex;align-items:center;justify-content:center;
      padding:2rem 1.5rem;
    }
    .success-title{display:flex;align-items:center;justify-content:center;gap:.75rem}
    .success-sub{color:var(--muted-foreground);text-align:center;margin-top:.5rem}

    /* Confete (apenas CSS, sutil) */
    .confetti{
      position:absolute; inset:0; pointer-events:none; overflow:hidden;
    }
    .confetti i{
      position:absolute; top:-10px; width:6px; height:10px; opacity:.9;
      background: currentColor; border-radius:2px;
      animation: fall linear forwards;
    }
    .confetti i:nth-child(3n){color:#5b8cff}
    .confetti i:nth-child(3n+1){color:#22c55e}
    .confetti i:nth-child(3n+2){color:#f59e0b}
    @keyframes fall{
      to{ transform: translateY(110vh) rotate(360deg); opacity:0.85 }
    }
    /* gerar alguns confetes via CSS puro */
    .confetti i:nth-child(1){ left:10%; animation-duration:2.8s }
    .confetti i:nth-child(2){ left:20%; animation-duration:3.2s; animation-delay:.1s }
    .confetti i:nth-child(3){ left:30%; animation-duration:2.6s; animation-delay:.2s }
    .confetti i:nth-child(4){ left:40%; animation-duration:3.4s; animation-delay:.05s }
    .confetti i:nth-child(5){ left:50%; animation-duration:2.9s; }
    .confetti i:nth-child(6){ left:60%; animation-duration:3.1s; animation-delay:.15s }
    .confetti i:nth-child(7){ left:70%; animation-duration:2.7s; animation-delay:.25s }
    .confetti i:nth-child(8){ left:80%; animation-duration:3.3s; animation-delay:.05s }
    .confetti i:nth-child(9){ left:90%; animation-duration:2.5s; animation-delay:.2s }
    .confetti i:nth-child(10){ left:15%; animation-duration:3.0s; animation-delay:.12s }
  </style>

  <!-- Ícones SVG embutidos -->
  <svg aria-hidden="true" style="position:absolute;width:0;height:0;overflow:hidden">
    <defs>
      <symbol id="i-message" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 15a4 4 0 0 1-4 4H7l-4 4V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v8z"/>
      </symbol>
      <symbol id="i-users" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
      </symbol>
      <symbol id="i-send" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/>
      </symbol>
      <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4 12 14l-3-3"/>
      </symbol>
    </defs>
  </svg>
</head>
<body>
  <!-- Header -->
  <header class="header shadow-sm">
    <div class="container px-6 py-4" style="display:flex;align-items:center;justify-content:space-between">
      <div class="row gap-3">
        <div class="logo-badge"><svg class="ic-xxl" style="width:1.5rem;height:1.5rem"><use href="#i-message"></use></svg></div>
        <div>
          <div class="text-2xl font-bold">QuickMessage</div>
          <div class="text-sm" style="opacity:.9">Seu app de mensagens instantâneas</div>
        </div>
      </div>
      <div class="row gap-4">
        <div class="badge row gap-2">
          <svg class="ic"><use href="#i-users"></use></svg>
          <span class="text-sm">1.2k usuários online</span>
        </div>
      </div>
    </div>
  </header>

  <!-- Conteúdo central -->
  <section class="center-wrap">
    <div class="container" style="position:relative">
      <!-- confete decorativo, opcional -->
      <div class="confetti" aria-hidden="true">
        <i></i><i></i><i></i><i></i><i></i>
        <i></i><i></i><i></i><i></i><i></i>
      </div>

      <article class="card shadow-lg" style="max-width:720px;margin-inline:auto">
        <div class="card__content">
          <div class="success-title">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:64px;height:64px;border-radius:50%;background:rgba(34,197,94,.12);border:1px solid rgba(34,197,94,.35)">
              <svg class="ic-xxl" style="color:var(--green)"><use href="#i-check"></use></svg>
            </span>
            <h1 class="text-3xl font-bold" style="margin:0">Mensagem enviada!</h1>
          </div>
          <p class="success-sub">
            Sua mensagem foi enviada com sucesso. Você pode voltar e enviar outra, ou seguir para o histórico.
          </p>

          <!-- Resumo/Placeholders (opcional, para você popular depois) -->
          <div style="margin-top:1.25rem;background:var(--accent);border:1px solid var(--border);border-radius:12px;padding:1rem">
            <dl style="display:grid;grid-template-columns:1fr;gap:.5rem;margin:0">
              <div style="display:flex;justify-content:space-between;gap:1rem">
                <dt class="text-sm muted">Para</dt>
                <dd class="text-sm" style="margin:0"><?= $recipientDisplay ?></dd>
              </div>
              <div style="display:flex;justify-content:space-between;gap:1rem">
                <dt class="text-sm muted">Assunto</dt>
                <dd class="text-sm" style="margin:0"><?= $subjectDisplay ?></dd>
              </div>
              <div style="display:flex;justify-content:space-between;gap:1rem">
                <dt class="text-sm muted">Horário</dt>
                <dd class="text-sm" style="margin:0"><?= $sentAtDisplay ?></dd>
              </div>
            </dl>
          </div>

          <!-- Ações -->
          <div class="btn-group" style="margin-top:1.5rem">
            <!-- Ajuste os hrefs conforme suas rotas -->
            <a class="btn btn--primary" href="quickmessage-ui.html">
              <svg class="ic"><use href="#i-send"></use></svg>
              Nova mensagem
            </a>
            <a class="btn btn--outline" href="#">
              <svg class="ic"><use href="#i-message"></use></svg>
              Ver histórico
            </a>
          </div>
        </div>
      </article>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="container" style="display:flex;align-items:center;justify-content:center;flex-direction:column">
      <div class="row gap-2 mb-2">
        <svg class="ic ic-primary"><use href="#i-message"></use></svg>
        <span class="font-semibold">QuickMessage</span>
      </div>
      <p class="text-sm muted">Conectando pessoas através de mensagens rápidas e seguras</p>
    </div>
  </footer>
</body>
</html>
