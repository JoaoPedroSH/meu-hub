<script setup>
import { Head, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    users: { type: Array, required: true },
    availableMenus: { type: Array, required: true },
});

const createForm = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    menu_permissions: props.availableMenus.map((menu) => menu.key),
});

const editableUsers = props.users
    .filter((user) => !user.is_primary_user)
    .map((user) => ({
        user,
        form: useForm({
            name: user.name,
            email: user.email,
            password: '',
            password_confirmation: '',
            menu_permissions: user.menu_permissions ?? props.availableMenus.map((menu) => menu.key),
        }),
    }));

function toggleMenu(form, menu) {
    const selected = form.menu_permissions.includes(menu);

    if (selected && form.menu_permissions.length === 1) {
        return;
    }

    form.menu_permissions = selected
        ? form.menu_permissions.filter((item) => item !== menu)
        : [...form.menu_permissions, menu];
}

function createUser() {
    createForm.post(route('settings.users.store'), {
        preserveScroll: true,
        onSuccess: () => createForm.reset(),
    });
}

function saveUser(row) {
    row.form.patch(route('settings.users.update', row.user.id), {
        preserveScroll: true,
        onSuccess: () => row.form.reset('password', 'password_confirmation'),
    });
}

function deleteUser(user) {
    if (window.confirm(`Remover a conta de ${user.name}? Os dados dessa conta também serão excluídos.`)) {
        router.delete(route('settings.users.destroy', user.id), { preserveScroll: true });
    }
}
</script>

<template>
    <Head title="Pessoas e acessos" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-5xl">
            <header class="border-b border-slate-200 pb-6">
                <nav class="mb-2 flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-400">
                    <span>Configurações</span><span>/</span><span class="text-slate-600">Pessoas e acessos</span>
                </nav>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Pessoas e acessos</h1>
                <p class="mt-1 text-sm text-slate-500">As contas pertencem a esta instalação. Cada pessoa mantém seus próprios dados e acessa apenas os menus autorizados.</p>
            </header>

            <section class="mt-8 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-slate-900">Adicionar pessoa</h2>
                    <p class="mt-1 text-sm text-slate-500">Defina os dados de entrada e os menus que estarão disponíveis.</p>
                </div>

                <form class="grid gap-4 sm:grid-cols-2" @submit.prevent="createUser">
                    <div>
                        <label for="new-name" class="text-sm font-medium text-slate-700">Nome</label>
                        <input id="new-name" v-model="createForm.name" required autocomplete="name" class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        <p v-if="createForm.errors.name" class="mt-1 text-xs text-rose-600">{{ createForm.errors.name }}</p>
                    </div>
                    <div>
                        <label for="new-email" class="text-sm font-medium text-slate-700">E-mail de acesso</label>
                        <input id="new-email" v-model="createForm.email" required type="email" autocomplete="username" class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        <p v-if="createForm.errors.email" class="mt-1 text-xs text-rose-600">{{ createForm.errors.email }}</p>
                    </div>
                    <div>
                        <label for="new-password" class="text-sm font-medium text-slate-700">Senha inicial</label>
                        <input id="new-password" v-model="createForm.password" required type="password" autocomplete="new-password" class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        <p v-if="createForm.errors.password" class="mt-1 text-xs text-rose-600">{{ createForm.errors.password }}</p>
                    </div>
                    <div>
                        <label for="new-password-confirmation" class="text-sm font-medium text-slate-700">Confirme a senha</label>
                        <input id="new-password-confirmation" v-model="createForm.password_confirmation" required type="password" autocomplete="new-password" class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    </div>

                    <fieldset class="sm:col-span-2">
                        <legend class="text-sm font-medium text-slate-700">Menus disponíveis</legend>
                        <div class="mt-3 flex flex-wrap gap-3">
                            <label v-for="menu in availableMenus" :key="menu.key" class="flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700">
                                <input v-model="createForm.menu_permissions" type="checkbox" :value="menu.key" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                                {{ menu.label }}
                            </label>
                        </div>
                        <p v-if="createForm.errors.menu_permissions || createForm.errors['menu_permissions.0']" class="mt-2 text-xs text-rose-600">
                            {{ createForm.errors.menu_permissions || createForm.errors['menu_permissions.0'] }}
                        </p>
                    </fieldset>

                    <div class="sm:col-span-2 flex justify-end">
                        <button type="submit" :disabled="createForm.processing" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:opacity-60">
                            {{ createForm.processing ? 'Criando…' : 'Criar conta local' }}
                        </button>
                    </div>
                </form>
            </section>

            <section class="mt-8 space-y-4">
                <h2 class="text-lg font-semibold text-slate-900">Contas desta instalação</h2>

                <article v-for="row in editableUsers" :key="row.user.id" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <form class="grid gap-4 sm:grid-cols-2" @submit.prevent="saveUser(row)">
                        <div>
                            <label :for="`name-${row.user.id}`" class="text-sm font-medium text-slate-700">Nome</label>
                            <input :id="`name-${row.user.id}`" v-model="row.form.name" required class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            <p v-if="row.form.errors.name" class="mt-1 text-xs text-rose-600">{{ row.form.errors.name }}</p>
                        </div>
                        <div>
                            <label :for="`email-${row.user.id}`" class="text-sm font-medium text-slate-700">E-mail</label>
                            <input :id="`email-${row.user.id}`" v-model="row.form.email" required type="email" class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            <p v-if="row.form.errors.email" class="mt-1 text-xs text-rose-600">{{ row.form.errors.email }}</p>
                        </div>
                        <div>
                            <label :for="`password-${row.user.id}`" class="text-sm font-medium text-slate-700">Nova senha <span class="font-normal text-slate-400">(opcional)</span></label>
                            <input :id="`password-${row.user.id}`" v-model="row.form.password" type="password" autocomplete="new-password" class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            <p v-if="row.form.errors.password" class="mt-1 text-xs text-rose-600">{{ row.form.errors.password }}</p>
                        </div>
                        <div>
                            <label :for="`password-confirmation-${row.user.id}`" class="text-sm font-medium text-slate-700">Confirme a nova senha</label>
                            <input :id="`password-confirmation-${row.user.id}`" v-model="row.form.password_confirmation" type="password" autocomplete="new-password" class="mt-1 w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>

                        <fieldset class="sm:col-span-2">
                            <legend class="text-sm font-medium text-slate-700">Menus disponíveis</legend>
                            <div class="mt-3 flex flex-wrap gap-3">
                                <label v-for="menu in availableMenus" :key="menu.key" class="flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700">
                                    <input
                                        type="checkbox"
                                        :checked="row.form.menu_permissions.includes(menu.key)"
                                        class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                        @change="toggleMenu(row.form, menu.key)"
                                    />
                                    {{ menu.label }}
                                </label>
                            </div>
                            <p v-if="row.form.errors.menu_permissions || row.form.errors['menu_permissions.0']" class="mt-2 text-xs text-rose-600">
                                {{ row.form.errors.menu_permissions || row.form.errors['menu_permissions.0'] }}
                            </p>
                        </fieldset>

                        <div class="sm:col-span-2 flex flex-col-reverse gap-3 border-t border-slate-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                            <button type="button" class="self-start text-sm font-medium text-rose-600 hover:text-rose-700" @click="deleteUser(row.user)">
                                Remover conta
                            </button>
                            <button type="submit" :disabled="row.form.processing" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:opacity-60">
                                {{ row.form.processing ? 'Salvando…' : 'Salvar alterações' }}
                            </button>
                        </div>
                    </form>
                </article>

                <div v-if="!editableUsers.length" class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500">
                    Ainda não há outras contas nesta instalação.
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
