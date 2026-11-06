<?php

return [
    'menu_items' => [
        [
            'key' => 'dashboard',
            'label' => 'Dashboard',
            'group' => 'Geral',
            'description' => 'Resumo e atalhos da sua instalação.',
            'permission' => 'dashboard',
            'level' => 0,
        ],
        [
            'key' => 'resumes',
            'label' => 'Currículos',
            'group' => 'Profissional',
            'description' => 'Crie, edite e organize seus currículos.',
            'permission' => 'resumes',
            'level' => 1,
        ],
        [
            'key' => 'notes',
            'label' => 'Anotações',
            'group' => 'Pessoal',
            'description' => 'Registre ideias, referências e lembretes.',
            'permission' => 'notes',
            'level' => 0,
        ],
        [
            'key' => 'calendar',
            'label' => 'Agenda',
            'group' => 'Pessoal',
            'description' => 'Organize compromissos e eventos.',
            'permission' => 'calendar',
            'level' => 0,
        ],
        [
            'key' => 'finance',
            'label' => 'Finanças',
            'group' => 'Finanças',
            'description' => 'Acesse as ferramentas financeiras da sua instalação.',
            'permission' => 'finance',
            'level' => 0,
        ],
        [
            'key' => 'finance.trader',
            'label' => 'Trader (simulação)',
            'group' => 'Finanças',
            'description' => 'Acompanhe o mercado spot e pratique operações sem dinheiro real.',
            'permission' => 'finance.trader',
            'level' => 1,
        ],
    ],
];
