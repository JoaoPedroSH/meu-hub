<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    canShowResumes: { type: Boolean, default: false },
    canShowNotes: { type: Boolean, default: false },
    canShowCalendar: { type: Boolean, default: false },
    resumesCount: { type: Number, default: 0 },
    notesCount: { type: Number, default: 0 },
    upcomingEventsCount: { type: Number, default: 0 },
    latestResume: { type: Object, default: null },
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-6xl">
            <header class="border-b border-slate-200 pb-6">
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-indigo-600">MeuHub</p>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    Olá, {{ $page.props.auth.user.name.split(' ')[0] }}
                </h1>
                <p class="mt-1 text-sm text-slate-500">Organize suas informações e compromissos em um só lugar.</p>
            </header>

            <section v-if="canShowResumes" class="mt-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-700">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <path d="M14 2v6h6M8 13h8M8 17h8" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-500">Profissional</p>
                            <h2 class="mt-1 text-xl font-semibold text-slate-900">Currículos</h2>
                            <p class="mt-1 text-sm text-slate-500">{{ resumesCount }} {{ resumesCount === 1 ? 'currículo salvo' : 'currículos salvos' }}</p>
                        </div>
                    </div>

                    <Link :href="route('resumes.index')" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                        {{ resumesCount ? 'Abrir currículos' : 'Criar currículo' }}
                    </Link>
                </div>

                <div v-if="latestResume" class="mt-7 border-t border-slate-100 pt-5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Última atualização</p>
                    <div class="mt-2 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <Link :href="route('resumes.show', latestResume.id)" class="font-semibold text-slate-900 hover:text-indigo-700">{{ latestResume.title }}</Link>
                            <p class="mt-1 text-sm text-slate-500">{{ latestResume.updated_at_formatted }} <span aria-hidden="true">·</span> {{ latestResume.updated_at_full }}</p>
                        </div>
                        <Link :href="route('resumes.edit', latestResume.id)" class="text-sm font-semibold text-indigo-700 hover:text-indigo-800">Continuar editando <span aria-hidden="true">→</span></Link>
                    </div>
                </div>

                <div v-else class="mt-7 border-t border-slate-100 pt-5 text-sm text-slate-500">
                    Seu primeiro currículo fica salvo somente nesta instalação.
                </div>
            </section>

            <section v-if="canShowNotes || canShowCalendar" class="mt-6 grid gap-4 sm:grid-cols-2">
                <Link v-if="canShowNotes" :href="route('notes.index')" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-amber-200 hover:shadow-md">
                    <p class="text-sm font-medium text-amber-700">Pessoal</p>
                    <h2 class="mt-1 text-lg font-semibold text-slate-900">Anotações</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ notesCount }} {{ notesCount === 1 ? 'anotação salva' : 'anotações salvas' }}</p>
                </Link>
                <Link v-if="canShowCalendar" :href="route('calendar.index')" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-indigo-200 hover:shadow-md">
                    <p class="text-sm font-medium text-indigo-700">Pessoal</p>
                    <h2 class="mt-1 text-lg font-semibold text-slate-900">Agenda</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ upcomingEventsCount }} {{ upcomingEventsCount === 1 ? 'compromisso futuro' : 'compromissos futuros' }}</p>
                </Link>
            </section>

            <section v-if="!canShowResumes && !canShowNotes && !canShowCalendar" class="mt-8 rounded-2xl border border-slate-200 bg-white p-6 text-sm leading-6 text-slate-600 shadow-sm sm:p-8">
                O acesso a Currículos não está habilitado nesta conta. Peça à pessoa responsável pela instalação para ajustar seus menus em Configurações.
            </section>
        </div>
    </AuthenticatedLayout>
</template>
