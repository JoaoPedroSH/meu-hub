# Skills do projeto

## Implementação

- Laravel concentra rotas, validação, autorização, persistência e geração de PDF.
- Vue + Inertia concentram a interação e os estados da interface.
- PostgreSQL é a fonte persistente dos dados.

## Revisão de interface

Verifique idioma pt-BR, responsividade, foco de teclado, estado vazio, feedback de sucesso/erro e consistência com `docs/padro-visual.md`.

## Segurança

O app é local, sem cadastro público ou autenticação SaaS centralizada. A conta principal gerencia contas locais e permissões de menu nas Configurações. Toda entidade de usuário deve ser filtrada pelo usuário autenticado. Ações de alteração, exclusão e acesso a menus devem ser autorizadas no servidor; nunca confiar somente na interface. A conta principal não ganha acesso implícito aos dados privados de outras contas.

## Escopo

Novas funcionalidades devem justificar sua presença no MVP e evitar abstrações prematuras. Categorias futuras não devem aparecer como menus vazios. Não introduzir painel administrativo separado, papéis de admin/cliente ou infraestrutura de SaaS.
