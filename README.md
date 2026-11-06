# MeuHub

O MeuHub é uma aplicação pessoal executada localmente. Cada instalação mantém seus próprios dados e pode ter uma conta principal e outras contas locais. Não há cadastro público nem serviço SaaS central.

O MVP concentra a navegação em **Profissional → Currículos**. A conta principal cria as demais contas e escolhe se cada pessoa pode acessar Dashboard e Currículos. Todas as contas usam a mesma interface; a gestão de pessoas fica em Configurações.

## Aplicação local

### Requisitos

- Docker e Docker Compose
- Git

### Inicialização

```sh
cp .env.example .env
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
docker compose exec node npm run build
```

Abra <http://localhost:8000>. Na primeira execução, o MeuHub apresenta a configuração inicial para criar a conta principal. Depois, entre com essa conta e cadastre outras pessoas em **Configurações → Pessoas e acessos**.

Os currículos são isolados por conta local. Remover uma conta também remove os currículos pertencentes a ela.

## Landing page e GitHub Pages

A landing page é um site estático separado do aplicativo Laravel, porque o GitHub Pages não executa PHP. Ela fica na branch `landing-page`, dentro de `landing/`, e o workflow de Pages publica somente essa pasta.

Para publicar, envie a branch `landing-page` ao GitHub e configure **Settings → Pages → Build and deployment → GitHub Actions**. O workflow publica a landing sem enviar o aplicativo local.

## Stack

- Laravel
- Vue.js + Inertia.js
- PostgreSQL
- Docker Compose para execução local
