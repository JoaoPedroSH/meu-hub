<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

function submit() {
    form.post(route('setup.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
}
</script>

<template>
    <GuestLayout>
        <Head title="Configuração inicial" />

        <div class="mb-6">
            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-indigo-600">
                Primeira execução
            </p>
            <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">
                Configure seu MeuHub
            </h1>
            <p class="mt-2 text-sm leading-6 text-slate-500">
                Crie a conta principal desta instalação local. Depois, você poderá adicionar outras pessoas e escolher quais menus elas acessam.
            </p>
        </div>

        <form class="space-y-4" @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Seu nome" />
                <TextInput id="name" v-model="form.name" class="mt-1 block w-full rounded-xl" required autofocus autocomplete="name" />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="E-mail para entrar" />
                <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full rounded-xl" required autocomplete="username" />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" value="Senha" />
                <TextInput id="password" v-model="form.password" type="password" class="mt-1 block w-full rounded-xl" required autocomplete="new-password" />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div>
                <InputLabel for="password_confirmation" value="Confirme a senha" />
                <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password" class="mt-1 block w-full rounded-xl" required autocomplete="new-password" />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="w-full rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 disabled:cursor-wait disabled:opacity-60"
            >
                {{ form.processing ? 'Configurando…' : 'Criar conta principal' }}
            </button>
        </form>
    </GuestLayout>
</template>
