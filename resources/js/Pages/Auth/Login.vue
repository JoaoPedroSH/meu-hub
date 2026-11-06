<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: String,
    demoCredentials: { type: Object, default: null },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

function submit() {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
}

function fillDemoCredentials() {
    if (!props.demoCredentials) {
        return;
    }

    form.email = props.demoCredentials.email;
    form.password = props.demoCredentials.password;
}
</script>

<template>
    <GuestLayout>
        <Head title="Entrar no MeuHub" />

        <div class="mb-6">
            <h1 class="text-xl font-bold text-slate-900">Entrar no MeuHub</h1>
            <p class="mt-1 text-sm leading-6 text-slate-500">
                Acesse suas ferramentas pessoais nesta instalação local.
            </p>
        </div>

        <div v-if="status" class="mb-4 text-sm font-medium text-emerald-700" role="status">
            {{ status }}
        </div>

        <div v-if="demoCredentials" class="mb-5 flex items-center justify-between gap-3 rounded-xl border border-indigo-100 bg-indigo-50/80 p-3.5 text-xs text-indigo-900">
            <div>
                <p class="font-semibold">Conta de demonstração</p>
                <p class="mt-1 text-indigo-700">Disponível somente em desenvolvimento.</p>
            </div>
            <button
                type="button"
                class="shrink-0 rounded-lg border border-indigo-200 bg-white px-2.5 py-1 font-semibold text-indigo-700 transition hover:bg-indigo-100"
                @click="fillDemoCredentials"
            >
                Preencher
            </button>
        </div>

        <form class="space-y-4" @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="E-mail" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full rounded-xl"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" value="Senha" />
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full rounded-xl"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input v-model="form.remember" type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                Lembrar neste dispositivo
            </label>

            <button
                type="submit"
                :disabled="form.processing"
                class="w-full rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 disabled:cursor-wait disabled:opacity-60"
            >
                {{ form.processing ? 'Entrando…' : 'Entrar' }}
            </button>

            <p class="pt-2 text-center text-xs leading-5 text-slate-500">
                As contas são criadas pela pessoa responsável nas configurações desta instalação.
            </p>
        </form>
    </GuestLayout>
</template>
