# MeuHub

## 1. Visão do produto

O **MeuHub** será um sistema pessoal para centralizar diferentes áreas da vida do usuário em um único lugar.

A visão de longo prazo é transformar o MeuHub em um **hub pessoal modular**, onde diferentes áreas da vida possuem ferramentas específicas.

Exemplo futuro:

```text
MeuHub
├── Profissional
│   ├── Currículo
│   ├── Experiências
│   ├── Formação
│   └── Projetos
│
├── Pessoal
├── Saúde
├── Estudos
├── Finanças
├── Planejamento
└── outras áreas
```

Porém, isso é apenas a visão futura.

## O MVP deve ser extremamente pequeno.

A primeira versão do MeuHub deve possuir **somente uma categoria e uma ferramenta**:

```text
Profissional
└── Currículo
```

Não implementar outras áreas neste momento.

O MeuHub não será um SaaS. Será uma aplicação instalada e executada localmente por cada pessoa ou organização, com dados próprios daquela instalação. Uma instalação poderá ter uma conta principal e outras contas locais, sem cadastro público ou dependência de um serviço central.

---

# 2. Objetivo do MVP

O objetivo inicial é criar uma ferramenta excelente para criação e gerenciamento de currículos.

O usuário deve conseguir entrar no MeuHub e:

1. criar um currículo;
2. editar o currículo;
3. salvar o currículo;
4. retornar posteriormente;
5. continuar a edição;
6. visualizar o currículo;
7. duplicar o currículo;
8. excluir o currículo;
9. exportar o currículo para PDF.

A experiência deve ser semelhante à de um **editor de documentos**, porém especializada em currículos.

A referência conceitual é:

> "Um Word simplificado especificamente para criar currículos."

Não é necessário reproduzir todas as funcionalidades de um editor de texto.

---

# 3. Stack

Utilizar obrigatoriamente:

- Laravel;
- Vue.js;
- Inertia.js;
- PostgreSQL.

A aplicação deve ser um **monólito Laravel + Vue + Inertia**, executado localmente. O site institucional/landing page é estático e independente do runtime Laravel; poderá ser publicado no GitHub Pages a partir de uma branch própria do mesmo repositório.

Não criar frontend e backend como aplicações independentes.

Laravel deve ser responsável por:

- aplicação;
- autenticação;
- regras de negócio;
- persistência;
- autorização;
- validação;
- geração/exportação quando apropriado.

Vue deve ser responsável pela interface.

Inertia deve fazer a comunicação entre Laravel e Vue.

PostgreSQL deve ser o banco de dados principal.

---

# 4. Arquitetura

Apesar de o MVP possuir apenas Currículo, a arquitetura deve permitir que o MeuHub cresça futuramente.

Utilizar o conceito:

```text
MeuHub
  ↓
Categoria
  ↓
Ferramenta
  ↓
Dados
```

No MVP:

```text
MeuHub
  ↓
Profissional
  ↓
Currículo
```

No futuro:

```text
MeuHub
├── Profissional
│   ├── Currículo
│   ├── Experiências
│   └── Projetos
│
├── Saúde
│   ├── Treinos
│   └── Atividades
│
└── Planejamento
    ├── Tarefas
    └── Objetivos
```

Não implementar essas funcionalidades futuras agora.

## Contas locais e acesso

Cada instalação possui uma conta principal, criada na configuração inicial. Essa conta pode cadastrar outras contas locais e definir os menus disponíveis para cada pessoa. Não há cadastro público, painel administrativo separado ou experiência distinta de “admin” e “cliente”. Todas as contas usam o mesmo layout; configurações de usuários e acessos ficam nas Configurações e aparecem conforme a autorização do usuário.

Os dados pessoais continuam protegidos por usuário. Cada pessoa acessa seus próprios currículos e demais dados, e toda autorização é validada no servidor. A conta principal pode administrar usuários e permissões de menu, mas isso não dá acesso automático ao conteúdo privado dos demais usuários.

---

# 5. Categorias pré-configuradas

As categorias do MeuHub devem existir internamente no sistema.

No futuro poderão existir:

- Profissional;
- Pessoal;
- Saúde;
- Estudos;
- Finanças;
- Planejamento;
- etc.

Porém, no MVP somente:

```text
Profissional
```

deve estar disponível.

O usuário não deve criar categorias livremente.

As categorias devem ser consideradas parte da estrutura interna do produto.

Isso permite que, no futuro, uma nova categoria seja adicionada pelo sistema sem alterar a arquitetura principal.

---

# 6. Profissional

A categoria Profissional deve conter inicialmente apenas:

```text
Currículo
```

A interface não deve apresentar funcionalidades que ainda não existem.

Não criar menus vazios como:

```text
Experiências
Formação
Projetos
Cursos
```

se eles ainda não forem ferramentas independentes.

Essas informações podem existir **dentro do currículo**.

---

# 7. Currículos

O usuário pode possuir vários currículos.

Exemplo:

```text
Meus currículos

Currículo Principal
Currículo Desenvolvedor
Currículo Gestão
Currículo Empresa X
```

Cada currículo deve ser independente.

Deve possuir:

- identificação;
- conteúdo;
- data de criação;
- data de atualização.

O usuário deve poder:

- criar;
- editar;
- duplicar;
- excluir;
- visualizar;
- exportar.

---

# 8. Editor de currículo

O principal diferencial do MVP deve ser o editor.

Não criar uma tela gigantesca composta apenas por inputs.

Criar uma experiência de edição semelhante a um documento.

O usuário deve conseguir editar o conteúdo do currículo de forma natural.

Estrutura inicial:

```text
Dados pessoais

Resumo profissional

Experiência profissional

Formação acadêmica

Cursos

Certificações

Habilidades

Idiomas

Informações adicionais
```

Cada seção deve poder ser editada.

O usuário deve conseguir:

- adicionar itens;
- editar itens;
- remover itens;
- reorganizar itens;
- alterar conteúdo;
- formatar textos quando necessário.

---

# 9. Dados pessoais

Suportar inicialmente:

- nome;
- telefone;
- e-mail;
- cidade;
- foto opcional;
- LinkedIn;
- GitHub;
- outros links.

Não exigir informações que não sejam necessárias.

---

# 10. Experiência profissional

Cada experiência deve permitir:

- empresa;
- cargo;
- localização;
- data inicial;
- data final;
- experiência atual;
- descrição.

A descrição deve permitir texto formatado.

---

# 11. Formação

Cada formação deve possuir:

- instituição;
- curso;
- grau/tipo;
- data inicial;
- data final;
- descrição opcional.

---

# 12. Cursos e certificações

Permitir cadastrar:

### Curso

- nome;
- instituição;
- data;
- descrição.

### Certificação

- nome;
- instituição;
- data;
- validade opcional;
- código opcional;
- link opcional.

---

# 13. Habilidades

Permitir cadastrar uma lista de habilidades.

Exemplo:

```text
PHP
Laravel
Vue.js
PostgreSQL
Git
Docker
```

A interface pode permitir reorganizar a ordem.

---

# 14. Idiomas

Cada idioma deve possuir:

- idioma;
- nível.

Exemplo:

```text
Português — Nativo
Inglês — Intermediário
Espanhol — Básico
```

---

# 15. Informações adicionais

Permitir uma seção de texto livre para informações que não se encaixem nas outras categorias.

---

# 16. Templates

No MVP deve existir apenas **um template de currículo**.

Esse template deve ser:

- profissional;
- limpo;
- legível;
- adequado para processos seletivos;
- responsivo na visualização;
- adequado para impressão/PDF.

A arquitetura deve permitir futuramente:

```text
Currículo
├── Template moderno
├── Template clássico
├── Template executivo
└── Template minimalista
```

Mas não implementar múltiplos templates agora.

---

# 17. Exportação PDF

O usuário deve conseguir exportar o currículo para PDF.

O resultado deve:

- possuir boa aparência;
- respeitar a hierarquia visual;
- funcionar em múltiplas páginas;
- evitar cortes de conteúdo;
- preservar acentuação;
- ser adequado para envio profissional.

Avaliar a melhor solução para geração do PDF dentro do ecossistema Laravel.

---

# 18. Dashboard

O dashboard do MeuHub deve ser simples.

Como existe apenas uma ferramenta no MVP, não tentar criar um dashboard cheio de gráficos.

Exemplo:

```text
Olá, João

Profissional

┌──────────────────────────────┐
│ Currículos                   │
│                              │
│ 2 currículos                 │
│                              │
│ Última atualização           │
│ Currículo Principal          │
│ Hoje às 10:30                │
│                              │
│ [ Criar currículo ]          │
└──────────────────────────────┘
```

O dashboard deve levar rapidamente o usuário à ação principal.

---

# 19. Navegação

A navegação deve ser preparada para o crescimento do MeuHub.

No MVP:

```text
MeuHub

Dashboard

Profissional
└── Currículos

Configurações
```

Não criar navegação para funcionalidades inexistentes.
O gerenciamento de usuários e permissões deve ser uma área das Configurações, não uma seção ou painel de Administração separado.

---

# 20. Design

O MeuHub deve possuir uma identidade visual moderna.

Priorizar:

- simplicidade;
- elegância;
- boa tipografia;
- espaçamento;
- hierarquia visual;
- responsividade;
- consistência;
- acessibilidade.

Evitar aparência de:

- sistema administrativo;
- ERP;
- painel cheio de tabelas;
- formulário governamental.

A sensação deve ser de um produto pessoal moderno.

---

# 21. Responsividade

O sistema deve funcionar em:

- desktop;
- notebook;
- tablet;
- celular.

O editor deve possuir uma experiência adequada para telas menores.

---

# 22. Persistência

Todos os dados do currículo devem ser persistidos no PostgreSQL.

O usuário deve conseguir:

```text
Criar
 ↓
Editar
 ↓
Salvar
 ↓
Sair
 ↓
Voltar
 ↓
Continuar editando
```

Nenhuma informação deve depender apenas do estado do frontend.

---

# 23. Segurança

Currículos possuem informações pessoais.

Garantir:

- autenticação;
- autorização;
- isolamento dos dados por usuário;
- gerenciamento local de usuários e permissões de menu pela conta principal;
- validação;
- proteção contra acesso indevido;
- políticas do Laravel quando apropriado.

Um usuário jamais deve conseguir visualizar ou alterar o currículo de outro usuário.

---

# 24. Modelo conceitual

Uma estrutura inicial pode seguir:

```text
User
 │
 └── Resume
      ├── PersonalInformation
      ├── Experiences
      ├── Educations
      ├── Courses
      ├── Certifications
      ├── Skills
      ├── Languages
      └── AdditionalInformation
```

A estrutura final deve ser definida após analisar o projeto e considerar as necessidades do editor.

Evitar colocar todo o currículo em um único campo JSON se isso prejudicar consultas, validações ou evolução do sistema.

Por outro lado, não normalizar excessivamente sem necessidade.

Escolher uma solução equilibrada.

---

# 25. Futuro

O MeuHub futuramente poderá crescer para:

```text
MeuHub
│
├── Profissional
│   ├── Currículo
│   ├── Carreira
│   └── Projetos
│
├── Pessoal
│
├── Saúde
│   ├── Treinos
│   ├── Atividades físicas
│   └── Hábitos
│
├── Estudos
│
├── Finanças
│
└── Planejamento
    ├── Tarefas
    ├── Objetivos
    └── Agenda
```

Mas essas áreas **não devem fazer parte do MVP**.

Quando forem implementadas, deverão seguir o mesmo conceito:

```text
Categoria
    ↓
Ferramentas especializadas
    ↓
Dados persistidos
```

---

# 26. O que NÃO fazer

Não implementar neste momento:

- categorias personalizadas;
- módulos personalizados;
- IA;
- integração com LinkedIn;
- integração com Google;
- agenda;
- tarefas;
- objetivos;
- hábitos;
- saúde;
- finanças;
- notificações;
- colaboração;
- cadastro público ou autenticação SaaS centralizada;
- painel administrativo separado;
- hierarquia de papéis admin/cliente;
- permissões granulares por registro ou campo (o MVP controla acesso por menu);
- marketplace;
- múltiplos templates de currículo.

Não criar funcionalidades apenas porque elas fazem parte da visão futura.

---

# 27. Ordem de implementação

## Etapa 1 — Análise

Antes de escrever código:

- analisar o projeto;
- verificar estrutura existente;
- verificar dependências;
- verificar banco;
- verificar autenticação;
- identificar padrões;
- identificar o que pode ser reutilizado.

Não substituir tecnologias ou arquitetura existentes sem necessidade.

---

## Etapa 2 — Fundação

Implementar:

- Laravel;
- Vue;
- Inertia;
- PostgreSQL;
- autenticação;
- layout;
- navegação;
- dashboard.

---

## Etapa 3 — Currículo

Implementar:

- criação;
- listagem;
- edição;
- visualização;
- exclusão;
- duplicação;
- persistência.

---

## Etapa 4 — Editor

Implementar a experiência de documento:

- seções;
- itens;
- edição;
- ordenação;
- formatação;
- preview.

---

## Etapa 5 — PDF

Implementar:

- template;
- renderização;
- exportação.

---

## Etapa 6 — Refinamento

Revisar:

- UX;
- responsividade;
- acessibilidade;
- validações;
- mensagens;
- estados vazios;
- performance;
- testes;
- segurança.

---

# 28. Critério de sucesso

O MVP estará concluído quando um usuário conseguir realizar todo este fluxo:

```text
Entrar no MeuHub
       ↓
Dashboard
       ↓
Profissional
       ↓
Currículos
       ↓
Criar currículo
       ↓
Preencher informações
       ↓
Editar o documento
       ↓
Salvar
       ↓
Voltar posteriormente
       ↓
Continuar edição
       ↓
Visualizar
       ↓
Exportar PDF
```

Essa experiência deve estar sólida antes de qualquer expansão do produto.

---

# 29. Diretriz principal para o agente

Não construa o Life OS inteiro.

Construa o **MeuHub MVP**.

O MeuHub deve começar com uma única coisa, mas fazê-la muito bem:

> **Criação, edição, persistência e exportação de currículos.**

A arquitetura deve estar preparada para receber novas categorias e ferramentas futuramente, mas o produto atual deve permanecer simples.

Priorize:

**Qualidade > quantidade**

**UX > complexidade**

**Produto funcional > arquitetura excessivamente abstrata**

**Código simples > abstrações prematuras**

Antes de cada implementação, pergunte:

> "Isso é necessário para o MVP do MeuHub?"

Se a resposta for não, deixe para uma etapa futura.

===

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

## Foundational Context

This application is a Laravel application running on PHP 8.4. Always use the APIs that match the installed major version of each package — do not assume a version.

Before relying on a package's API, confirm its installed version:
- PHP packages: run `composer show --direct` to list direct dependencies with versions, or `composer show <vendor/package>` for a single package.
- JS packages: check `package.json` for the installed versions.

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If a frontend change doesn't show in the UI or you get a "Unable to locate file in Vite manifest" error, run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Use `search-docs` before changes that depend on Laravel ecosystem APIs, behavior, configuration, or version-specific syntax. Skip it for copy-only edits and other changes where package documentation is irrelevant. Reuse sufficient results already in context instead of searching again.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Project Rules

- This project contains committed, area-grouped rules in `.ai/rules` when that directory exists, including path-scoped framework guidelines under `.ai/rules/boost`. Before you enter plan mode or create/edit any file, you MUST first: open @.ai/rules/index.md (it maps file globs to rule files), read every rule file whose globs cover the path(s) in scope, and run `grep -rin 'keyword' .ai/rules` to catch what a path match alone misses. Do not write code until you have read and are following every matching rule. If `.ai/rules` does not exist, continue without it.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.
- Activate the `deploying-to-cloud` skill whenever deploying to Laravel Cloud, configuring Cloud environments or resources, using the Cloud CLI, or troubleshooting Cloud deployments.

=== tests rules ===

# Test Enforcement

- Add or update tests for behavior and logic changes when a test provides meaningful regression coverage.
- Pure copy, styling, and layout-only changes do not require new or updated tests.
- When test coverage applies, run the affected tests and ensure they pass.
- Test the changed behavior and its important failure modes, but do not add tests beyond them.
- Read the `testing-best-practices` skill before writing tests.

=== inertia-laravel/core rules ===

# Inertia

- Inertia creates fully client-side rendered SPAs without modern SPA complexity, leveraging existing server-side patterns.
- Components live in `resources/js/Pages` (unless specified in `vite.config.js`). Use `Inertia::render()` for server-side routing instead of Blade views.
- ALWAYS use `search-docs` tool for version-specific Inertia documentation and updated code examples.
- IMPORTANT: Activate `inertia-vue-development` when working with Inertia Vue client-side patterns.

# Inertia v2

- Use all Inertia features from v1 and v2. Check the documentation before making changes to ensure the correct approach.
- New features: deferred props, infinite scroll, merging props, polling, prefetching, once props, flash data.
- When using deferred props, add an empty state with a pulsing or animated skeleton.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== phpunit/core rules ===

# PHPUnit

- This project uses PHPUnit. Create tests with `php artisan make:test --phpunit {name}`.
- Do not include the test suite directory in `{name}`. Use `SomeFeatureTest`, not `Feature/SomeFeatureTest`.
- Read the `testing-best-practices` skill for guidance on coverage, naming, structure, dependency isolation, and review.

## Running Tests

- Run the narrowest set of tests that covers the change. Pass a file path or `--filter=testName` to `php artisan test --compact`.
- Rerun a test after each change to it.
- Run `vendor/bin/phpunit` to call the test runner directly. It accepts the same file path and `--filter=testName` arguments.

=== inertia-vue/core rules ===

# Inertia + Vue

Vue components must have a single root element.
- IMPORTANT: Activate `inertia-vue-development` when working with Inertia Vue client-side patterns.

</laravel-boost-guidelines>
