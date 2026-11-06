<script setup>
import { computed, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const page = usePage();
const showingMobileMenu = ref(false);
const menus = computed(() => page.props.auth?.menus ?? {});
const homeUrl = computed(() => {
    if (menus.value.dashboard) {
        return route('dashboard');
    }

    if (menus.value.resumes) {
        return route('resumes.index');
    }

    if (menus.value.notes) {
        return route('notes.index');
    }

    if (menus.value.calendar) {
        return route('calendar.index');
    }

    if (menus.value.trader) {
        return route('finance.trader.index');
    }

    if (menus.value.finance) {
        return route('finance.index');
    }

    return route('profile.edit');
});

function closeMobileMenu() {
    showingMobileMenu.value = false;
}
</script>

<template>
    <div class="flex min-h-screen flex-col bg-slate-50 text-slate-800 md:flex-row">
        <aside class="fixed inset-y-0 z-30 hidden w-64 flex-col border-r border-slate-200 bg-white md:flex">
            <div class="flex h-16 items-center border-b border-slate-100 px-6">
                <Link :href="homeUrl" class="flex items-center"><ApplicationLogo /></Link>
            </div>

            <div class="flex flex-1 flex-col justify-between overflow-y-auto px-4 py-6">
                <nav aria-label="Navegação principal" class="space-y-6">
                    <section v-if="menus.dashboard">
                        <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Geral</p>
                        <Link
                            :href="route('dashboard')"
                            :aria-current="route().current('dashboard') ? 'page' : undefined"
                            :class="route().current('dashboard') ? 'bg-slate-900 font-medium text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition"
                        >
                            <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                            Dashboard
                        </Link>
                    </section>

                    <section v-if="menus.finance || menus.trader">
                        <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Finanças</p>
                        <Link
                            v-if="menus.finance"
                            :href="route('finance.index')"
                            :aria-current="route().current('finance.index') ? 'page' : undefined"
                            :class="route().current('finance.index') ? 'bg-emerald-50 font-medium text-emerald-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition"
                        >
                            <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20M7 15h3"/></svg>
                            Visão financeira
                        </Link>
                        <Link
                            v-if="menus.trader"
                            :href="route('finance.trader.index')"
                            :aria-current="route().current('finance.trader.*') ? 'page' : undefined"
                            :class="route().current('finance.trader.*') ? 'bg-emerald-50 font-medium text-emerald-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'"
                            class="flex items-center gap-3 rounded-xl py-2.5 pr-3 text-sm transition pl-9"
                        >
                            Trader · Simulação
                        </Link>
                    </section>

                    <section v-if="menus.resumes">
                        <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Profissional</p>
                        <Link
                            :href="route('resumes.index')"
                            :aria-current="route().current('resumes.*') ? 'page' : undefined"
                            :class="route().current('resumes.*') ? 'bg-indigo-50 font-medium text-indigo-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition"
                        >
                            <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M8 13h8M8 17h8"/></svg>
                            Currículos
                        </Link>
                    </section>

                    <section v-if="menus.notes || menus.calendar">
                        <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Pessoal</p>
                        <Link
                            v-if="menus.notes"
                            :href="route('notes.index')"
                            :aria-current="route().current('notes.*') ? 'page' : undefined"
                            :class="route().current('notes.*') ? 'bg-amber-50 font-medium text-amber-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition"
                        >
                            <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 4h16v16H4z"/><path d="M8 8h8M8 12h6M8 16h4"/></svg>
                            Anotações
                        </Link>
                        <Link
                            v-if="menus.calendar"
                            :href="route('calendar.index')"
                            :aria-current="route().current('calendar.*') ? 'page' : undefined"
                            :class="route().current('calendar.*') ? 'bg-indigo-50 font-medium text-indigo-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition"
                        >
                            <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                            Agenda
                        </Link>
                    </section>

                    <section>
                        <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Conta</p>
                        <Link
                            :href="route('profile.edit')"
                            :aria-current="route().current('profile.*') || route().current('settings.users.*') ? 'page' : undefined"
                            :class="route().current('profile.*') || route().current('settings.users.*') ? 'bg-slate-100 font-medium text-slate-900' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition"
                        >
                            <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33h-.08a1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82v-.08a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09a1.65 1.65 0 0 0 1.51-1v-.08a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.6h.08a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51h.08a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9v.08a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                            Configurações
                        </Link>
                    </section>
                </nav>

                <div class="flex items-center justify-between gap-3 border-t border-slate-100 pt-4">
                    <div class="flex min-w-0 items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-900 text-xs font-bold text-white">{{ page.props.auth.user.name.charAt(0).toUpperCase() }}</div>
                        <div class="min-w-0">
                            <p class="truncate text-xs font-semibold text-slate-800">{{ page.props.auth.user.name }}</p>
                            <p class="truncate text-[11px] text-slate-400">{{ page.props.auth.user.email }}</p>
                        </div>
                    </div>
                    <Link :href="route('logout')" method="post" as="button" aria-label="Sair da conta" title="Sair" class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5M21 12H9"/></svg>
                    </Link>
                </div>
            </div>
        </aside>

        <div class="flex min-h-screen flex-1 flex-col md:pl-64">
            <header class="sticky top-0 z-40 border-b border-slate-200 bg-white md:hidden">
                <div class="flex h-16 items-center justify-between px-4">
                    <Link :href="homeUrl"><ApplicationLogo /></Link>
                    <button type="button" class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600" :aria-expanded="showingMobileMenu" aria-controls="mobile-navigation" aria-label="Alternar navegação" @click="showingMobileMenu = !showingMobileMenu">
                        <svg v-if="!showingMobileMenu" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg v-else class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 18 12-12M6 6l12 12"/></svg>
                    </button>
                </div>
                <nav v-if="showingMobileMenu" id="mobile-navigation" aria-label="Navegação principal" class="space-y-1 border-t border-slate-100 bg-white px-4 py-3">
                    <Link v-if="menus.dashboard" :href="route('dashboard')" class="block rounded-xl px-3 py-2.5 text-sm text-slate-700 hover:bg-slate-100" @click="closeMobileMenu">Dashboard</Link>
                    <Link v-if="menus.resumes" :href="route('resumes.index')" class="block rounded-xl px-3 py-2.5 text-sm text-slate-700 hover:bg-slate-100" @click="closeMobileMenu">Profissional · Currículos</Link>
                    <Link v-if="menus.notes" :href="route('notes.index')" class="block rounded-xl px-3 py-2.5 text-sm text-slate-700 hover:bg-slate-100" @click="closeMobileMenu">Pessoal · Anotações</Link>
                    <Link v-if="menus.calendar" :href="route('calendar.index')" class="block rounded-xl px-3 py-2.5 text-sm text-slate-700 hover:bg-slate-100" @click="closeMobileMenu">Pessoal · Agenda</Link>
                    <Link v-if="menus.finance" :href="route('finance.index')" class="block rounded-xl px-3 py-2.5 text-sm text-slate-700 hover:bg-slate-100" @click="closeMobileMenu">Finanças</Link>
                    <Link v-if="menus.trader" :href="route('finance.trader.index')" class="block rounded-xl px-3 py-2.5 pl-7 text-sm text-slate-700 hover:bg-slate-100" @click="closeMobileMenu">Finanças · Trader (simulação)</Link>
                    <Link :href="route('profile.edit')" class="block rounded-xl px-3 py-2.5 text-sm text-slate-700 hover:bg-slate-100" @click="closeMobileMenu">Configurações</Link>
                    <Link :href="route('logout')" method="post" as="button" class="block w-full rounded-xl px-3 py-2.5 text-left text-sm font-medium text-rose-600 hover:bg-rose-50" @click="closeMobileMenu">Sair da conta</Link>
                </nav>
            </header>

            <div v-if="page.props.flash?.success" class="border-b border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 sm:px-8" role="status">{{ page.props.flash.success }}</div>
            <div v-if="page.props.flash?.error" class="border-b border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 sm:px-8" role="alert">{{ page.props.flash.error }}</div>

            <header v-if="$slots.header" class="border-b border-slate-200 bg-white px-4 py-4 sm:px-8">
                <div class="mx-auto max-w-7xl"><slot name="header" /></div>
            </header>

            <main class="flex-1 px-4 py-8 sm:px-8">
                <div class="mx-auto max-w-7xl"><slot /></div>
            </main>
        </div>
    </div>
</template>
