# Padrão visual do MeuHub

## Direção

O MeuHub é um produto pessoal, calmo e modular. A interface deve transmitir clareza, autonomia e leveza — nunca a sensação de um ERP ou de um formulário burocrático.

## Fundamentos

- **Idioma:** português do Brasil em toda a interface visível.
- **Tipografia:** sistema sans-serif; títulos com peso 700/800 e textos de apoio com contraste reduzido.
- **Cores:** fundo `slate-50`, superfícies brancas, texto `slate-900`, apoio `slate-500`.
- **Ação principal:** `slate-900` com texto branco.
- **Destaques:** índigo para navegação/profissional, âmbar para anotações, esmeralda para confirmações e rosa para ações destrutivas.
- **Forma:** cartões com raio `rounded-2xl`, bordas sutis e sombra discreta.
- **Espaçamento:** usar múltiplos de 4; preferir respiro generoso a excesso de informação.

## Componentes

Inputs, selects e textareas usam borda `slate-300`, raio `rounded-xl`, foco índigo e mensagens de validação abaixo do campo. Botões devem ter altura confortável, texto curto e estado de carregamento.

## Layout

Desktop usa navegação lateral fixa de 16rem. Em telas menores, a navegação vira cabeçalho recolhível. Conteúdos devem ocupar no máximo 7xl e preservar margens laterais de 1rem a 2rem.

## Cabeçalhos de página

As páginas de ferramenta usam o mesmo padrão de Currículos: breadcrumb em caixa alta (`Geral / Nome da ferramenta`), título `text-2xl font-bold`, subtítulo curto abaixo e ação principal alinhada à direita. Agenda e Anotações pertencem à categoria Geral; “espaço pessoal” pode aparecer apenas como linguagem de apoio, não como categoria.

## Ações de itens

Editar usa ícone de lápis em botão discreto `slate`, e remover usa ícone de lixeira com hover `rose`, seguindo o padrão de Currículos. Ações ficam agrupadas no canto superior direito do cartão.

## Acessibilidade

Todo controle deve ter texto ou `aria-label`, contraste suficiente, foco visível e alternativa para telas pequenas. Confirmações destrutivas são explícitas.

## Administração e configurações

Administradores navegam apenas pelas categorias Geral, Administração e Sistema. O menu Usuários fica em Administração. Configurações usa abas Perfil / Usuário, Integrações e Notificações; a aba de Notificações permanece como estado vazio até a feature existir. Atalhos do Dashboard e do painel administrativo são preferências persistidas e só oferecem destinos autorizados.

## Currículo e PDF

O template único de currículo usa A4, margens de 15mm, hierarquia tipográfica objetiva e blocos que evitam quebra no meio de um item. A visualização web segue a mesma hierarquia do PDF.

## Novas áreas

Anotações usam superfície branca; somente tags são coloridas. A tela usa lista lateral rolável e editor de texto à direita. Tags podem ser removidas do catálogo, mas ficam preservadas visualmente nas notas existentes. Notas podem receber data, horário, dia inteiro e cor de evento para gerar/atualizar um compromisso na agenda. Agenda usa índigo como cor-base, com cores de evento selecionáveis. A relação entre nota e evento é opcional e não cria subgrupos de notas.
