# Help-Desk (PHP)

Sistema simples de Help Desk para abertura, acompanhamento e gestão de chamados.  
Ideal para estudos e prática de PHP, com foco em autenticação, organização de pastas e boas práticas.

## ✨ Funcionalidades

- **Autenticação por sessão**
  - Login/Logout
  - Proteção de rotas
  - CSRF token nos formulários

- **Perfis de acesso**
  - **Usuário:** abre chamados, lista seus chamados, atualiza perfil
  - **Admin (IDs 1 e 2):** painel para ver todos os chamados, filtrar e marcar como concluídos

- **Chamados**
  - Abertura com título, descrição e setor
  - Listagem com filtros (status, busca, período)
  - Ação de finalizar/fechar (somente admin)

- **Frontend**
  - Interface simples e responsiva (HTML/CSS/JS vanilla)

## 🚀 Como rodar o projeto

### 🐳 Rodando com Docker

O projeto pode ser executado facilmente com Docker. Não é necessário configurar banco de dados, pois os arquivos em `/database` são apenas exemplos e scripts.

1. Certifique-se de ter o [Docker](https://docs.docker.com/get-docker/) e o [Docker Compose](https://docs.docker.com/compose/install/) instalados.
2. No terminal, execute:
   ```bash
   docker-compose up --build
   ```
3. Acesse [http://localhost:3000](http://localhost:3000) no navegador.

> **Nota:** O diretório `/database` contém apenas arquivos de exemplo e scripts, não há banco de dados rodando em container.

## 📁 Estrutura de Pastas

- `/public` — arquivos públicos (index.php, CSS, JS)
- `/app` — lógica de negócio, controllers, models
- `/views` — templates HTML
- `/database` — contém apenas arquivos de exemplo e scripts
- `/config.php` — configurações gerais

## 🛡️ Segurança

- Autenticação por sessão
- Proteção CSRF nos formulários
- Validação de entrada de dados

## 👨‍💻 Contribuição

Pull requests são bem-vindos! Sinta-se à vontade para abrir issues ou sugerir melhorias.

---

Projeto didático para fins de estudo e evolução em PHP.