# Padrão visual do MeuHub

## Direção

O MeuHub é um produto pessoal, calmo e modular. A interface deve transmitir clareza, autonomia e leveza — nunca a sensação de um ERP ou de um formulário burocrático.

## Fundamentos

- **Idioma:** português do Brasil em toda a interface visível.
- **Tipografia:** sistema sans-serif; títulos com peso 700/800 e textos de apoio com contraste reduzido.
- **Cores:** fundo `slate-50`, superfícies brancas, texto `slate-900`, apoio `slate-500`.
- **Ação principal:** `slate-900` com texto branco.
- **Destaques:** índigo para navegação/profissional, esmeralda para confirmações e rosa para ações destrutivas.
- **Forma:** cartões com raio `rounded-2xl`, bordas sutis e sombra discreta.
- **Espaçamento:** usar múltiplos de 4; preferir respiro generoso a excesso de informação.

## Componentes

Inputs, selects e textareas usam borda `slate-300`, raio `rounded-xl`, foco índigo e mensagens de validação abaixo do campo. Botões devem ter altura confortável, texto curto e estado de carregamento.

## Layout

Desktop usa navegação lateral fixa de 16rem. Em telas menores, a navegação vira cabeçalho recolhível. Conteúdos devem ocupar no máximo 7xl e preservar margens laterais de 1rem a 2rem.

## Cabeçalhos de página

As páginas de ferramenta usam o mesmo padrão de Currículos: breadcrumb em caixa alta (`Profissional / Currículos`), título `text-2xl font-bold`, subtítulo curto abaixo e ação principal alinhada à direita.

## Ações de itens

Editar usa ícone de lápis em botão discreto `slate`, e remover usa ícone de lixeira com hover `rose`, seguindo o padrão de Currículos. Ações ficam agrupadas no canto superior direito do cartão.

## Acessibilidade

Todo controle deve ter texto ou `aria-label`, contraste suficiente, foco visível e alternativa para telas pequenas. Confirmações destrutivas são explícitas.

## Configurações locais

O MeuHub não tem painel administrativo separado nem interface de “admin” e “cliente”. Todas as contas locais usam o mesmo layout. A conta principal gerencia usuários e permissões de menu dentro de Configurações; essa área só aparece para quem tem autorização. As opções de menu respeitam as permissões configuradas, e o servidor também bloqueia acessos não autorizados. Não exibir abas ou destinos de funcionalidades que ainda não existem no MVP.

## Currículo e PDF

O template único de currículo usa A4, margens de 15mm, hierarquia tipográfica objetiva e blocos que evitam quebra no meio de um item. A visualização web segue a mesma hierarquia do PDF.

O MVP exibe apenas Currículos. Outras ferramentas e categorias só entram na navegação quando forem implementadas.
