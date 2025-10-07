<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>QuickMessage - UI</title>
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
    body{margin:0;font-family:Inter,ui-sans-serif,system-ui,Segoe UI,Roboto,Helvetica,Arial;
      background:var(--background);color:var(--foreground);line-height:1.45}
    .container{max-width:1120px;margin-inline:auto}
    .px-6{padding-inline:1.5rem}.py-4{padding-block:1rem}.py-8{padding-block:2rem}
    .mt-16{margin-top:4rem}.mb-2{margin-bottom:.5rem}.text-sm{font-size:.9rem}
    .text-2xl{font-size:1.5rem}.font-bold{font-weight:700}.font-semibold{font-weight:600}
    .row{display:flex;align-items:center;gap:.5rem}.gap-2{gap:.5rem}.gap-3{gap:.75rem}.gap-4{gap:1rem}
    .shadow-sm{box-shadow:0 4px 16px rgba(0,0,0,.25)} .shadow-lg{box-shadow:var(--shadow)}
    .w-full{width:100%}
    .header{background:var(--primary);color:var(--primary-foreground)}
    .badge{background:rgba(255,255,255,.12);padding:.25rem .75rem;border-radius:999px;font-size:.85rem}
    .logo-badge{background:rgba(255,255,255,.2);padding:.5rem;border-radius:12px}

    .grid{display:grid;grid-template-columns:1fr;gap:2rem}
    @media (min-width:1024px){.grid{grid-template-columns:2fr 1fr}}

    .card{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden}
    .card__header{padding:1.1rem 1.25rem;border-bottom:1px solid var(--border)}
    .card__title{font-weight:700;font-size:1.05rem;display:flex;align-items:center;gap:.5rem}
    .card__content{padding:1.25rem}
    .card--accent{background:var(--accent)}

    .field{display:flex;flex-direction:column;gap:.5rem}
    .label{font-size:.9rem;font-weight:600}
    .input,.textarea{
      width:100%;padding:.9rem 1rem;background:#0b0e14;color:var(--foreground);
      border:1px solid var(--border);border-radius:12px;outline:none;transition:border .15s, box-shadow .15s
    }
    .input:focus,.textarea:focus{border-color:var(--primary);box-shadow:0 0 0 4px var(--ring)}
    .textarea{min-height:8rem;resize:vertical}

    .btn{
      display:inline-flex;align-items:center;justify-content:center;gap:.5rem;height:3rem;padding:0 1rem;
      font-weight:700;border-radius:12px;border:1px solid transparent;cursor:pointer;
      transition:transform .06s, filter .15s, background .2s, border .2s
    }
    .btn:active{transform:translateY(1px)}
    .btn--primary{background:var(--primary);color:var(--primary-foreground)}
    .btn--outline{background:transparent;color:var(--foreground);border-color:var(--border)}
    .btn--block{width:100%}

    .stat-row{display:flex;align-items:center;justify-content:space-between}
    .muted{color:var(--muted-foreground)}

    .footer{background:var(--muted);padding:2rem 0;border-top:1px solid var(--border)}
    .ic{width:1.1rem;height:1.1rem}.ic-xl{width:1.5rem;height:1.5rem}
    .ic-primary{color:var(--primary)} .ic-green{color:var(--green)} .ic-orange{color:var(--orange)}
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
      <symbol id="i-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
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
        <div class="logo-badge"><svg class="ic-xl"><use href="#i-message"></use></svg></div>
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

  <!-- Main -->
  <main class="container px-6 py-8">
    <!-- placeholders para mensagens globais (você controla depois) -->
    <div id="globalError" class="card" style="display:none;padding:0.75rem 1rem;border-left:4px solid #ef4444">
      <p class="text-sm">Erro.</p>
    </div>
    <div id="globalSuccess" class="card" style="display:none;padding:0.75rem 1rem;border-left:4px solid #22c55e;margin-top:1rem">
      <p class="text-sm">Sucesso.</p>
    </div>

    <div class="grid">
      <!-- Formulário -->
    <form action="../core/shipping_process.php" method="post" style="display:flex;flex-direction:column;gap:2rem">
      <article class="card shadow-lg">
        <div class="card__header">
          <div class="card__title">
            <svg class="ic ic-primary"><use href="#i-send"></use></svg> Enviar Nova Mensagem
          </div>
        </div>

        <div class="card__content" style="display:flex;flex-direction:column;gap:1rem">
          <div class="field">
            <label class="label" for="recipient">Para</label>
            <input class="input" id="recipient" type="email" placeholder="usuario@exemplo.com" autocomplete="email" name="para" required />
            <small id="err-recipient" style="display:none;color:#fca5a5;font-size:.85rem;margin-top:.35rem">Informe um e-mail válido.</small>
          </div>

          <div class="field">
            <label class="label" for="subject">Assunto</label>
            <input class="input" id="subject" type="text" placeholder="Assunto da mensagem" name="assunto" required />
            <small id="err-subject" style="display:none;color:#fca5a5;font-size:.85rem;margin-top:.35rem">Informe o assunto.</small>
          </div>

          <div class="field">
            <label class="label" for="message">Mensagem</label>
            <textarea name="mensagem" class="textarea" id="message" placeholder="Digite sua mensagem aqui..." required></textarea>
            <small id="err-message" style="display:none;color:#fca5a5;font-size:.85rem;margin-top:.35rem">Escreva a mensagem.</small>
          </div>
          <button class="btn btn--primary btn--block" type="submit">
            <svg class="ic"><use href="#i-send"></use></svg>
            <span>Enviar Mensagem</span>
          </button>
        </div>
      </article>
    </form>


      <!-- Sidebar -->
      <aside style="display:flex;flex-direction:column;gap:1.5rem">
        <article class="card">
          <div class="card__header"><div class="card__title">Estatísticas</div></div>
          <div class="card__content" style="display:flex;flex-direction:column;gap:1rem">
            <div class="stat-row">
              <div class="row gap-2">
                <svg class="ic ic-primary"><use href="#i-send"></use></svg>
                <span class="text-sm">Enviadas hoje</span>
              </div>
              <span class="font-semibold">24</span>
            </div>
            <div class="stat-row">
              <div class="row gap-2">
                <svg class="ic ic-green"><use href="#i-check"></use></svg>
                <span class="text-sm">Entregues</span>
              </div>
              <span class="font-semibold">22</span>
            </div>
            <div class="stat-row">
              <div class="row gap-2">
                <svg class="ic ic-orange"><use href="#i-clock"></use></svg>
                <span class="text-sm">Pendentes</span>
              </div>
              <span class="font-semibold">2</span>
            </div>
          </div>
        </article>

        <article class="card">
          <div class="card__header"><div class="card__title">Ações Rápidas</div></div>
          <div class="card__content" style="display:flex;flex-direction:column;gap:.6rem">
            <button class="btn btn--outline btn--block" type="button">
              <svg class="ic" style="margin-right:.25rem"><use href="#i-users"></use></svg>Contatos
            </button>
            <button class="btn btn--outline btn--block" type="button">
              <svg class="ic" style="margin-right:.25rem"><use href="#i-clock"></use></svg>Histórico
            </button>
            <button class="btn btn--outline btn--block" type="button">
              <svg class="ic" style="margin-right:.25rem"><use href="#i-message"></use></svg>Modelos
            </button>
          </div>
        </article>

        <article class="card card--accent">
          <div class="card__header"><div class="card__title">Dica</div></div>
          <div class="card__content">
            <p class="text-sm muted">Use assuntos claros e objetivos para melhorar a taxa de abertura das suas mensagens.</p>
          </div>
        </article>
      </aside>
    </div>
  </main>

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
