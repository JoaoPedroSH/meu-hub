# Arquitetura do MeuHub

O produto é um monólito Laravel + Vue + Inertia com PostgreSQL, executado localmente em cada instalação. A landing page é um site estático separado do runtime Laravel e pode ser publicada no GitHub Pages em uma branch própria do mesmo repositório. O domínio segue a direção `Categoria → Ferramenta → Dados`.

No MVP, a categoria Profissional contém apenas Currículos. Não exibir Anotações, Agenda ou outras ferramentas até que sejam explicitamente incluídas no escopo.

Cada instalação pode ter uma conta principal e contas locais adicionais. A conta principal cria e gerencia usuários e suas permissões de menu nas Configurações. Todas as pessoas usam a mesma aplicação e o mesmo layout; não há painel separado de administração, cadastro público ou serviço de identidade central. Dados de currículo são isolados por usuário e nunca ficam acessíveis apenas por possuir a conta principal.

Modelos devem manter relações pequenas e explícitas. Use políticas ou verificações de propriedade no servidor, migrations reversíveis e testes de feature para fluxos de usuário. Permissões de menu devem ser validadas no servidor e também refletidas na navegação.
