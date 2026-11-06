<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({ canShowTrader: { type: Boolean, default: false } });
</script>

<template>
    <Head title="Finanças" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-5xl">
            <header class="border-b border-slate-200 pb-6">
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-emerald-700">MeuHub · Pessoal</p>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Finanças</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">Área inicial para ferramentas financeiras pessoais. O primeiro recurso está em modo de simulação.</p>
            </header>

            <Link v-if="canShowTrader" :href="route('finance.trader.index')" class="mt-8 flex flex-col gap-5 rounded-2xl border border-emerald-100 bg-white p-6 shadow-sm transition hover:border-emerald-300 hover:shadow-md sm:flex-row sm:items-center sm:justify-between sm:p-8">
                <div>
                    <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Simulação · sem dinheiro real</span>
                    <h2 class="mt-3 text-xl font-semibold text-slate-900">Trader</h2>
                    <p class="mt-1 max-w-xl text-sm leading-6 text-slate-500">Acompanhe BTC, ETH e SOL no mercado spot e pratique compras e vendas com carteiras virtuais.</p>
                </div>
                <span class="inline-flex shrink-0 items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white">Abrir Trader <span class="ml-2" aria-hidden="true">→</span></span>
            </Link>

            <p v-else class="mt-8 rounded-2xl border border-slate-200 bg-white p-6 text-sm leading-6 text-slate-500">Nenhuma ferramenta financeira está habilitada para esta conta. A pessoa principal pode configurar os acessos em Configurações → Pessoas e acessos.</p>
        </div>
    </AuthenticatedLayout>
</template>
