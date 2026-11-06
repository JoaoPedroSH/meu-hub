# Rascunho e plano: Finanças → Trader

Este documento registra o esboço e o plano da área de trading pessoal do MeuHub. A implementação inicial já oferece cotações públicas de cripto spot e carteiras de simulação; não conecta contas de corretoras nem envia ordens reais.

## 1. Esboço do produto

### Navegação

```text
MeuHub
├── Geral
│   └── Dashboard
├── Profissional
│   └── Currículos
├── Finanças
│   ├── Visão financeira
│   └── Trader · Simulação (implementado, item independente)
└── Configurações
    ├── Perfil e segurança
    ├── Pessoas e acessos (conta principal)
    └── Personalizar menu
```

Cada instalação mantém a autorização de acesso e a preferência visual do menu como coisas distintas. A autorização define quais recursos a conta pode acessar; a preferência define quais desses recursos aparecem na navegação daquela pessoa. Cada item terá uma chave própria — por exemplo, `finance` e `finance.trader` — para que ocultar ou mostrar Trader não altere os outros itens de Finanças. Um item só deve aparecer como opção de personalização depois de implementado e autorizado para a conta.

### Tela inicial de Trader

```text
Finanças / Trader

Modo: Simulação                         [Pausar operações]

Carteiras locais                       [Criar carteira]
┌──────────────────────────────────────────────────────────┐
│ Carteira de simulação                  Saldo em USDT       │
│ Nenhuma conta de corretora conectada                     │
└──────────────────────────────────────────────────────────┘

Mercado e estratégia
┌──────────────────────────────────────────────────────────┐
│ Mercado: [Cripto spot ▼]  Símbolo: [BTCUSDT ▼]           │
│ Estratégia/IA: ainda não habilitadas                      │
└──────────────────────────────────────────────────────────┘

Risco da sessão
┌──────────────────────────────────────────────────────────┐
│ Limite de perda diária       R$ [____]                    │
│ Risco máximo por ordem       R$ [____]                    │
│ Ordens/posições simultâneas  [____]                       │
│ [ ] Exigir minha aprovação para cada ordem                │
└──────────────────────────────────────────────────────────┘

Atividade recente: sinais, aprovações, ordens e bloqueios
```

O esboço pressupõe vários vínculos de conta, mas configurações e limites independentes para cada conta. Um botão de emergência deve interromper novas ordens em todas as contas e cancelar ordens abertas quando a API permitir.

## 2. Viabilidade dos mercados

### Decisão para a primeira implementação

Para começar tecnicamente com menos dependências, foi escolhido **cripto spot em simulação**. A Binance documenta um endpoint público para consulta de preço spot sem chave de API; o MeuHub usa essa fonte para exibir BTC/USDT, ETH/USDT e SOL/USDT e registra compras e vendas apenas em carteiras locais simuladas. Isso reduz a integração inicial a uma leitura pública de mercado e evita guardar credenciais, movimentar saldo ou enviar ordens a uma corretora. [Binance Spot Testnet e exemplo de endpoint público](https://developers.binance.com/en/docs/products/spot/testnet).

Essa escolha descreve a integração técnica mais simples para a fase de aprendizagem, não uma recomendação de investimento nem uma decisão sobre o primeiro mercado para operações reais. Ativos virtuais são voláteis, e o Banco Central passou a disciplinar prestadores de serviços de ativos virtuais em 2026; qualquer conector real precisa ser escolhido e validado à luz das regras e autorizações vigentes. [Banco Central — regulamentação de ativos virtuais](https://www.bcb.gov.br/detalhenoticia/20918/nota?s=08).

| Mercado | Viabilidade técnica | Considerações para uma instalação brasileira | Prioridade sugerida |
| --- | --- | --- | --- |
| Futuros negociados na B3 | Viável se uma corretora oferecer API de negociação adequada à conta. Cada corretora pode exigir credenciamento, assinatura de dados, homologação e regras próprias. | Há contratos listados e regras públicas de margem, mas a alavancagem pode ampliar perdas rapidamente. As APIs da própria B3 para integração são voltadas a clientes B2B; não são uma API direta de ordens para pessoa física. A integração precisaria ser com uma corretora autorizada que permita automação. [B3 for Developers](https://developers.b3.com.br/) e [características do futuro de Ibovespa](https://www.b3.com.br/pt_br/produtos-e-servicos/negociacao/renda-variavel/mercado-de-acoes/futuro-de-ibovespa.htm). | **Alternativa potencial para fase real futura**, após encontrar uma corretora/API compatível e validar o fluxo de homologação. |
| Forex/CFDs | Algumas plataformas oferecem APIs, contas demo e envio de ordens. A OANDA, por exemplo, documenta API REST e ambiente de prática; isso demonstra viabilidade técnica, não autorização para oferta a residentes no Brasil. [OANDA REST v20](https://developer.oanda.com/rest-live-v20/development-guide/). | A CVM informa em seu alerta sobre CFDs/Forex que não havia oferta registrada nem corretora autorizada pela CVM para esse mercado na publicação consultada, e alerta para riscos regulatórios, de contraparte e de fraude. Confirmar a situação atual com a CVM e assessoria jurídica antes de qualquer integração ou divulgação. [Alerta CVM sobre Forex e CFDs](https://www.gov.br/investidor/pt-br/educacional/publicacoes-educacionais/alertas/alerta_cvm_forex_2024.pdf). | **Não habilitar para contas reais na primeira versão.** Reavaliar somente após validação regulatória e do provedor. |
| Opções binárias | Há plataformas que automatizam esse tipo de contrato, mas APIs e condições variam e podem ser opacas. | A CVM já alertou que a Deriv/Binary.com não estava autorizada a intermediar valores mobiliários ou captar investidores residentes no Brasil. No Reino Unido, a FCA proíbe a venda de opções binárias a consumidores de varejo e classifica ofertas como provável fraude. Isso não equivale a uma opinião jurídica universal sobre todo produto chamado “binário”, mas torna esse segmento uma escolha inadequada para a primeira integração. [Alerta da CVM](https://www.gov.br/cvm/pt-br/assuntos/noticias/2023/cvm-alerta-para-atuacao-irregular-de-deriv-com-e-binary-com), [orientação FCA](https://www.fca.org.uk/consumers/binary-options-scams). | **Não integrar.** Evitar conectar contas ou enviar ordens para plataformas desse tipo. |

### Recomendação

Não tentar conectar “qualquer corretora e qualquer mercado” de uma vez. A implementação começou com simulação spot e cotações públicas porque é o caminho técnico mais simples: Binance publica preços spot sem API key. Isso não habilita negociação real. Para a primeira integração real, comparar duas opções com o usuário: um prestador de ativos virtuais autorizado e com ambiente de teste adequado, ou uma corretora brasileira autorizada com API de futuros B3. A B3 informa que suas APIs próprias são destinadas a clientes B2B e não oferecem acesso direto a pessoas físicas; portanto, a integração B3 teria de usar uma API da corretora. Em ambos os casos, validar autorização, termos, sandbox, escopos e suporte antes de pedir credenciais.

Os formatos de conta e os mercados permitidos não são universais. Cada conexão deve declarar suas capacidades: mercados, ativos, tipos de ordem, dados disponíveis e se permite apenas leitura, simulação ou negociação real. O usuário poderá vincular várias contas, mas cada ordem será enviada explicitamente para uma conta selecionada; não haverá transferência de saldo entre contas.

## 3. IA e execução segura

### Separar análise de execução

1. **Coleta:** preços, candles, posições, saldo e calendário de mercado através de provedores autorizados.
2. **Análise:** a IA produz uma proposta estruturada — ativo, direção, hipótese, horizonte, invalidação, riscos e validade do sinal. A IA não recebe segredo de corretora e não chama a API de ordens.
3. **Validação determinística:** um motor de risco confere saldo, exposição, limites, mercado aberto, preço atualizado, ativo permitido e duplicidade da ordem.
4. **Autorização:** no início, a pessoa aprova cada proposta. Automação completa fica desligada por padrão e só poderá ser ativada separadamente por conta, mercado e estratégia.
5. **Execução e auditoria:** um adaptador da corretora envia uma ordem já validada, acompanha seu estado e registra proposta, decisão, limites aplicados, resposta do broker e cancelamentos.

Um modelo de linguagem não deve ter permissão para transformar texto livre em ordens. Para o modo automático, ele só pode selecionar uma ação dentro de estratégias previamente configuradas e de limites imutáveis no servidor. Uma recomendação da IA não representa previsão confiável nem garantia de retorno.

### Controles obrigatórios antes de qualquer modo real

- Modo real desligado por padrão, com confirmação explícita e reautenticação para ativar.
- Modo de leitura, modo de simulação, modo com aprovação por ordem e modo automático como estados distintos e visíveis.
- Limites por conta: perda diária, risco monetário por ordem, tamanho máximo, alavancagem/exposição, número de posições e ativos autorizados.
- Bloqueio automático ao atingir limites; não permitir que a IA aumente ou contorne os limites.
- Botão de parada global e por conta; cancelar ordens abertas quando suportado pela corretora.
- Rejeitar ordem se cotação estiver atrasada, sessão estiver fechada, conexão estiver instável, estado da ordem for desconhecido ou relógio/dados estiverem inconsistentes.
- Chave de idempotência, reconciliação de ordens e posições com o broker e circuit breaker para erros/rejeições repetidos.
- Credenciais somente com escopo necessário, preferindo leitura no início; nunca pedir permissão de saque. Criptografar tokens no banco, não registrá-los em logs e permitir revogar/desconectar a conta.
- Histórico imutável de sinais, aprovações, ordens, alterações de configuração, versão da estratégia/modelo e decisões do motor de risco.
- Avisos claros de risco, teste de restauração de backup e procedimento de desligamento manual.

Como o MeuHub é local, os segredos e a chave de criptografia ficam na máquina da instalação. Isso evita centralizar credenciais no MeuHub, mas não protege uma máquina comprometida; backups, permissões do sistema operacional e proteção da chave `APP_KEY` também serão parte do desenho.

Se o produto passar a recomendar, analisar ou gerir investimentos para outras pessoas, ou enviar ordens por conta de terceiros, será necessária análise regulatória e jurídica antes de oferecer essa função. A CVM lista atividades como análise, consultoria e administração de carteiras entre as que podem exigir autorização em operações de valores mobiliários. [Posicionamento da CVM sobre Forex e atividades relacionadas](https://www.gov.br/cvm/pt-br/acesso-a-informacao-cvm/perguntas-frequentes-da-cvm/mercado-forex-qual-o-posicionamento).

## 4. Plano de implementação

### Fase 0 — Descoberta e limites

- Escolher a jurisdição de uso inicial e obter revisão jurídica/regulatória.
- Consultar corretoras autorizadas e confirmar API de ordens, suporte à conta pessoa física, mercados, preços de dados, sandbox, homologação e termos para automação.
- Não coletar tokens reais enquanto essa avaliação estiver pendente.
- Definir instrumento inicial, horário, fonte de dados e método de cálculo de risco.

**Saída:** decisão documentada de mercado/provedor suportados ou decisão de permanecer somente em simulação.

### Fase 1 — Esqueleto navegável

- Registrar `Finanças` e `Trader` no catálogo de menus quando a área estiver pronta para aparecer.
- Manter preferências por item, com chave independente para o grupo e cada submenu, como `finance` e `finance.trader`.
- Criar telas vazias apenas como protótipo de desenvolvimento; não expor itens sem funcionalidade na navegação normal.
- Definir modelos de conta, conexão e estratégia sem armazenar segredo em texto puro.

**Saída:** navegação e telas de apresentação, sem conexão externa ou ordem.

### Fase 2 — Simulação local

- Implementar importação/reprodução de dados de mercado e conta simulada. **O primeiro recorte já está implementado:** preços públicos BTC/USDT, ETH/USDT e SOL/USDT, múltiplas carteiras locais, ordens fictícias à vista, saldo, posições, histórico e taxa estimada.
- Implementar registro de sinais, ordens fictícias, posições, taxas estimadas e P&L.
- Implementar backtest com custos, slippage e prevenção de look-ahead bias; separar claramente resultado histórico de execução simulada.
- Exercitar os limites e o botão de parada.

**Saída:** fluxo de análise e decisão demonstrável sem credenciais ou capital real.

### Fase 3 — Conector somente leitura

- Implementar um adaptador para uma corretora escolhida e somente leitura.
- Guardar credenciais criptografadas e validar revogação, indisponibilidade e expiração.
- Sincronizar saldo, posições e ordens, sem criar ou alterar ordens.
- Validar reconciliação e trilha de auditoria.

**Saída:** contas reais conectadas sem capacidade de negociação.

### Fase 4 — Aprovação manual em conta de simulação

- Enviar ordens de simulação por uma API oficial quando disponível.
- Exibir proposta completa, tamanho, preço estimado, risco máximo, validade e destino da conta antes de aprovar.
- Implementar idempotência, cancelamento, atualização de estado e circuito de falha.

**Saída:** ciclo completo de aprovação e execução simulada com histórico auditável.

### Fase 5 — Piloto real restrito

- Habilitar apenas depois da revisão jurídica, validação do broker e aprovação consciente do usuário.
- Começar com uma conta, um mercado, poucos ativos permitidos, ordens pequenas e aprovação obrigatória por ordem.
- Manter limites rígidos no servidor, observabilidade local e botão de parada testado.
- Não iniciar automação contínua/autônoma nesta fase.

**Saída:** piloto controlado, acompanhado e reversível.

### Fase 6 — Automação opcional

- Considerar automação somente após período suficiente de simulação e piloto manual, métricas definidas e revisão independente.
- Habilitar por conta e estratégia, com limites, janela de operação, limite de perda, expiração automática e confirmação/reautorização periódica.
- Desligar automaticamente diante de perda máxima, API instável, divergência de posição, dados atrasados ou alteração de estratégia.

**Saída:** execução limitada por políticas independentes da IA e comprovável pelo histórico.

## 5. Critérios de aceite para uma futura versão real

- Nenhum segredo pode aparecer em frontend, logs ou respostas de erro.
- Uma conta sem permissão de negociação não pode enviar ordens.
- Conta, mercado, ativo e estratégia precisam estar explicitamente habilitados.
- A ordem é bloqueada no servidor se qualquer limite falhar, mesmo que a interface tenha sido manipulada.
- A mesma proposta não pode criar ordens duplicadas após retry ou timeout.
- Perda diária, preço desatualizado, sessão fechada e conexão interrompida acionam bloqueio automático.
- O usuário consegue desconectar uma conta e revogar o token no broker.
- Todo sinal, aprovação, ordem, resposta, cancelamento e bloqueio tem registro auditável.
- Testes cobrem limites e falhas de broker antes de qualquer ativação real.

## Referências consultadas

- [B3 for Developers — público B2B e ausência de acesso direto para pessoa física](https://developers.b3.com.br/)
- [B3 — Futuro de Ibovespa, margem e características do contrato](https://www.b3.com.br/pt_br/produtos-e-servicos/negociacao/renda-variavel/mercado-de-acoes/futuro-de-ibovespa.htm)
- [CVM — alerta sobre ofertas de Forex e CFDs](https://www.gov.br/investidor/pt-br/educacional/publicacoes-educacionais/alertas/alerta_cvm_forex_2024.pdf)
- [CVM — alerta sobre Deriv.com/Binary.com](https://www.gov.br/cvm/pt-br/assuntos/noticias/2023/cvm-alerta-para-atuacao-irregular-de-deriv-com-e-binary-com)
- [FCA — riscos e restrição britânica sobre opções binárias para varejo](https://www.fca.org.uk/consumers/binary-options-scams)
- [OANDA — documentação oficial REST v20 e ambiente de prática](https://developer.oanda.com/rest-live-v20/development-guide/)
- [CVM — atividades relacionadas a Forex que podem exigir autorização](https://www.gov.br/cvm/pt-br/acesso-a-informacao-cvm/perguntas-frequentes-da-cvm/mercado-forex-qual-o-posicionamento)
