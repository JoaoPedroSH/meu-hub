<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';

const page = usePage();
</script>

<template>
    <Head title="Configurações" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-4xl">
            <header class="border-b border-slate-200 pb-6">
                <nav class="mb-2 flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-400">
                    <span>Geral</span><span>/</span><span class="text-slate-600">Configurações</span>
                </nav>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Configurações</h1>
                <p class="mt-1 text-sm text-slate-500">Atualize seus dados de acesso e sua senha.</p>
            </header>

            <div class="mt-8 space-y-6">
                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8">
                    <UpdateProfileInformationForm />
                </section>

                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8">
                    <UpdatePasswordForm />
                </section>

                <Link
                    :href="route('settings.menu.edit')"
                    class="flex items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-indigo-200 hover:shadow-md sm:p-6"
                >
                    <div>
                        <h2 class="font-semibold text-slate-900">Personalizar menu</h2>
                        <p class="mt-1 text-sm text-slate-500">Escolha quais funcionalidades autorizadas aparecem na sua navegação.</p>
                    </div>
                    <span class="shrink-0 text-sm font-semibold text-indigo-700">Personalizar <span aria-hidden="true">→</span></span>
                </Link>

                <Link
                    v-if="page.props.auth.canManageUsers"
                    :href="route('settings.users.index')"
                    class="flex items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-indigo-200 hover:shadow-md sm:p-6"
                >
                    <div>
                        <h2 class="font-semibold text-slate-900">Pessoas e acessos</h2>
                        <p class="mt-1 text-sm text-slate-500">Adicione pessoas e escolha os menus disponíveis para cada conta.</p>
                    </div>
                    <span class="shrink-0 text-sm font-semibold text-indigo-700">Gerenciar <span aria-hidden="true">→</span></span>
                </Link>

                <Link
                    v-if="page.props.auth.menus.calendar"
                    :href="route('settings.integrations.index')"
                    class="flex items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-indigo-200 hover:shadow-md sm:p-6"
                >
                    <div>
                        <h2 class="font-semibold text-slate-900">Integrações</h2>
                        <p class="mt-1 text-sm text-slate-500">Conecte sua conta do Google Agenda a esta instalação.</p>
                    </div>
                    <span class="shrink-0 text-sm font-semibold text-indigo-700">Configurar <span aria-hidden="true">→</span></span>
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
