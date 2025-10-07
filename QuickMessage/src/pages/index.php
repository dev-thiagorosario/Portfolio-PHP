<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>QuickMessage - HTML/CSS</title>
  <style>
    /* ========= Design Tokens (estilo shadcn/tailwind-like) ========= */
    :root{
      --background: #0b0c0f;          /* fundo app */
      --foreground: #e9eef8;          /* texto padrão */
      --primary: #5b8cff;             /* cor principal */
      --primary-foreground: #ffffff;  /* texto sobre primary */
      --muted: #13151a;               /* blocos de fundo */
      --muted-foreground: #a7b0c0;    /* texto fraco */
      --accent: #141b24;              /* dica */
      --card: #0f1116;                /* cartão */
      --border: #20232b;              /* bordas sutis */
      --ring: rgba(91,140,255,.35);   /* foco */
      --green: #22c55e;
      --orange: #f59e0b;
      --shadow: 0 10px 30px rgba(0,0,0,.35);
      --radius: 16px;
    }

    /* ========= Reset elegante ========= */
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0;
      font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Inter, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji","Segoe UI Emoji";
      background: var(--background);
      color: var(--foreground);
      line-height:1.45;
    }
    img,svg{display:block}

    /* ========= Utilitários ========= */
    .container{max-width:1120px;margin-inline:auto}
    .container--narrow{max-width:960px;margin-inline:auto}
    .px-6{padding-left:1.5rem;padding-right:1.5rem}
    .py-4{padding-top:1rem;padding-bottom:1rem}
    .py-8{padding-top:2rem;padding-bottom:2rem}
    .mt-16{margin-top:4rem}
    .mb-2{margin-bottom:.5rem}
    .text-sm{font-size:.9rem}
    .text-lg{font-size:1.05rem}
    .text-2xl{font-size:1.5rem}
    .font-bold{font-weight:700}
    .font-semibold{font-weight:600}
    .center{display:flex;align-items:center;justify-content:center}
    .row{display:flex;align-items:center;gap:.5rem}
    .gap-2{gap:.5rem}
    .gap-3{gap:.75rem}
    .gap-4{gap:1rem}
    .shadow-sm{box-shadow:0 4px 16px rgba(0,0,0,.25)}
    .shadow-lg{box-shadow:var(--shadow)}
    .rounded{border-radius: var(--radius)}
    .rounded-md{border-radius: 12px}
    .rounded-lg{border-radius: 14px}
    .rounded-full{border-radius:999px}
    .w-full{width:100%}

    /* ========= Header ========= */
    .header{
      background: var(--primary);
      color: var(--primary-foreground);
    }
    .header__left .logo-badge{
      background: rgba(255,255,255,.2);
      padding:.5rem;
      border-radius:12px;
    }
    .badge{
      background: rgba(255,255,255,.12);
      padding:.25rem .75rem;
      border-radius:999px;
      font-size:.85rem;
    }

    /* ========= Grid principal ========= */
    .grid{
      display:grid;
      grid-template-columns: 1fr;
      gap:2rem;
    }
    @media (min-width: 1024px){
      .grid{
        grid-template-columns: 2fr 1fr;
      }
    }

    /* ========= Cards ========= */
    .card{
      background: var(--card);
      border:1px solid var(--border);
      border-radius: var(--radius);
      overflow: hidden;
    }
    .card__header{
      padding:1.1rem 1.25rem;
      border-bottom:1px solid var(--border);
    }
    .card__title{
      font-weight:700;
      font-size:1.05rem;
      display:flex;
      align-items:center;
      gap:.5rem;
    }
    .card__content{
      padding:1.25rem;
    }
    .card--accent{ background: var(--accent); }

    /* ========= Form ========= */
    .field{display:flex;flex-direction:column;gap:.5rem}
    .label{font-size:.9rem;font-weight:600;color:var(--foreground)}
    .input,.textarea{
      width:100%;
      padding: .9rem 1rem;
      background: #0b0e14;
      color: var(--foreground);
      border:1px solid var(--border);
      border-radius: 12px;
      outline: none;
      transition: border .15s, box-shadow .15s;
    }
    .textarea{min-height:8rem; resize: vertical}
    .input:focus,.textarea:focus{
      border-color: var(--primary);
      box-shadow: 0 0 0 4px var(--ring);
    }

    /* ========= Botões ========= */
    .btn{
      display:inline-flex;align-items:center;justify-content:center;
      gap:.5rem;
      height:3rem;
      padding:0 1rem;
      font-weight:700;
      border-radius:12px;
      border:1px solid transparent;
      cursor:pointer;
      transition: transform .06s ease, filter .15s ease, background .2s ease, border .2s ease;
      user-select:none;
      -webkit-tap-highlight-color: transparent;
    }
    .btn:active{ transform: translateY(1px) }
    .btn--primary{
      background: var(--primary);
      color: var(--primary-foreground);
    }
    .btn--primary:disabled{
      filter:saturate(.4) brightness(.8);
      cursor:not-allowed;
    }
    .btn--outline{
      background: transparent;
      color: var(--foreground);
      border-color: var(--border);
    }
    .btn--block{ width:100% }

    /* ========= Linhas de estatística ========= */
    .stat-row{display:flex;align-items:center;justify-content:space-between}
    .muted{color: var(--muted-foreground)}

    /* ========= Footer ========= */
    .footer{
      background: var(--muted);
      padding:2rem 0;
      border-top:1px solid var(--border);
    }

    /* ========= Ícones (SVG tamanho) ========= */
    .ic{width:1.1rem;height:1.1rem}
    .ic-lg{width:1.25rem;height:1.25rem}
    .ic-xl{width:1.5rem;height:1.5rem}
    .ic-primary{color:var(--primary)}
    .ic-green{color:var(--green)}
    .ic-orange{color:var(--orange)}

    /* ========= Loader ========= */
    @keyframes spin{ to{ transform: rotate(360deg) } }
    .spinner{
      width:1rem;height:1rem;
      border:2px solid currentColor;
      border-top-color: transparent;
      border-radius:999px;
      animation: spin .8s linear infinite;
    }

    /* ========= Helpers ========= */
    .maxw-6xl{max-width: 1200px; margin-inline:auto}
  </style>

  <!-- Símbolos SVG (ícones) -->
  <svg aria-hidden="true" style="position:absolute; width:0; height:0; overflow:hidden">
    <defs>
      <!-- MessageCircle -->
      <symbol id="i-message" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 15a4 4 0 0 1-4 4H7l-4 4V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v8z"/>
      </symbol>
      <!-- Users -->
      <symbol id="i-users" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
        <path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/>
        <circle cx="9" cy="7" r="4"/>
        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
      </symbol>
      <!-- Send -->
      <symbol id="i-send" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
        <path d="M22 2L11 13"/>
        <path d="M22 2l-7 20-4-9-9-4 20-7z"/>
      </symbol>
      <!-- Clock -->
      <symbol id="i-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/>
        <path d="M12 6v6l4 2"/>
      </symbol>
      <!-- CheckCircle -->
      <symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
        <path d="M22 4 12 14l-3-3"/>
      </symbol>
    </defs>
  </svg>
</head>
<body>
  <!-- Header -->
  <header class="header shadow-sm">
    <div class="container px-6 py-4" style="display:flex;align-items:center;justify-content:space-between">
      <div class="header__left row gap-3">
        <div class="logo-badge">
          <svg class="ic-xl"><use href="#i-message"></use></svg>
        </div>
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
    <div class="grid">
      <!-- Coluna formulário -->
      <section class="col-main">
        <article class="card shadow-lg">
          <div class="card__header">
            <div class="card__title">
              <svg class="ic ic-primary"><use href="#i-send"></use></svg>
              Enviar Nova Mensagem
            </div>
          </div>
          <div class="card__content" style="display:flex;flex-direction:column;gap:1rem">
            <div class="field">
              <label class="label" for="recipient">Para</label>
              <input class="input" id="recipient" type="email" placeholder="usuario@exemplo.com" />
            </div>

            <div class="field">
              <label class="label" for="subject">Assunto</label>
              <input class="input" id="subject" type="text" placeholder="Assunto da mensagem" />
            </div>

            <div class="field">
              <label class="label" for="message">Mensagem</label>
              <textarea class="textarea" id="message" placeholder="Digite sua mensagem aqui..."></textarea>
            </div>

            <button id="sendBtn" class="btn btn--primary btn--block">
              <svg class="ic"><use href="#i-send"></use></svg>
              <span>Enviar Mensagem</span>
            </button>
          </div>
        </article>
      </section>

      <!-- Sidebar -->
      <aside class="col-side" style="display:flex;flex-direction:column;gap:1.5rem">
        <!-- Estatísticas -->
        <article class="card">
          <div class="card__header">
            <div class="card__title">Estatísticas</div>
          </div>
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

        <!-- Ações rápidas -->
        <article class="card">
          <div class="card__header">
            <div class="card__title">Ações Rápidas</div>
          </div>
          <div class="card__content" style="display:flex;flex-direction:column;gap:.6rem">
            <button class="btn btn--outline btn--block" type="button">
              <svg class="ic" style="margin-right:.25rem"><use href="#i-users"></use></svg>
              Contatos
            </button>
            <button class="btn btn--outline btn--block" type="button">
              <svg class="ic" style="margin-right:.25rem"><use href="#i-clock"></use></svg>
              Histórico
            </button>
            <button class="btn btn--outline btn--block" type="button">
              <svg class="ic" style="margin-right:.25rem"><use href="#i-message"></use></svg>
              Modelos
            </button>
          </div>
        </article>

        <!-- Dica -->
        <article class="card card--accent">
          <div class="card__header">
            <div class="card__title">Dica</div>
          </div>
          <div class="card__content">
            <p class="text-sm muted">
              Use assuntos claros e objetivos para melhorar a taxa de abertura das suas mensagens.
            </p>
          </div>
        </article>
      </aside>
    </div>
  </main>

  <!-- Footer -->
  <footer class="footer">
    <div class="maxw-6xl center" style="flex-direction:column" >
      <div class="row gap-2 mb-2">
        <svg class="ic ic-primary"><use href="#i-message"></use></svg>
        <span class="font-semibold">QuickMessage</span>
      </div>
      <p class="text-sm muted">Conectando pessoas através de mensagens rápidas e seguras</p>
    </div>
  </footer>

  <script>
    // ======== Estado e lógica do botão ========
    const recipient = document.getElementById('recipient');
    const subject   = document.getElementById('subject');
    const message   = document.getElementById('message');
    const sendBtn   = document.getElementById('sendBtn');

    let isLoading = false;
    let isSent    = false;

    function setBtnStateDefault(){
      sendBtn.disabled = false;
      sendBtn.innerHTML = `
        <svg class="ic"><use href="#i-send"></use></svg>
        <span>Enviar Mensagem</span>
      `;
    }
    function setBtnStateLoading(){
      sendBtn.disabled = true;
      sendBtn.innerHTML = `
        <span class="spinner"></span>
        <span>Enviando...</span>
      `;
    }
    function setBtnStateSent(){
      sendBtn.disabled = true;
      sendBtn.innerHTML = `
        <svg class="ic"><use href="#i-check"></use></svg>
        <span>Mensagem Enviada!</span>
      `;
    }
    function validateFields(){
      const hasAll = recipient.value.trim() && subject.value.trim() && message.value.trim();
      // só desativa quando não está carregando/sent
      if(!isLoading && !isSent){
        sendBtn.disabled = !hasAll;
      }
    }

    recipient.addEventListener('input', validateFields);
    subject.addEventListener('input', validateFields);
    message.addEventListener('input', validateFields);

    sendBtn.addEventListener('click', () => {
      if(sendBtn.disabled) return;
      if(!recipient.value.trim() || !subject.value.trim() || !message.value.trim()) return;

      isLoading = true;
      setBtnStateLoading();

      // Simula envio (1.5s)
      setTimeout(() => {
        isLoading = false;
        isSent = true;
        setBtnStateSent();

        // Após 2s reseta
        setTimeout(() => {
          isSent = false;
          recipient.value = '';
          subject.value = '';
          message.value = '';
          setBtnStateDefault();
          validateFields();
        }, 2000);
      }, 1500);
    });

    // estado inicial do botão
    validateFields();
  </script>
</body>
</html>
