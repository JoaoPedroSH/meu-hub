<script setup>
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    menuGroups: { type: Array, default: () => [] },
    hiddenMenuItems: { type: Array, default: () => [] },
});

const form = useForm({
    hidden_menu_items: [...props.hiddenMenuItems],
});

const hasMenuItems = computed(() => props.menuGroups.some((group) => group.items.length > 0));

function toggleMenuItem(key, isVisible) {
    const hiddenMenuItems = new Set(form.hidden_menu_items);

    if (isVisible) {
        hiddenMenuItems.delete(key);
    } else {
        hiddenMenuItems.add(key);
    }

    form.hidden_menu_items = [...hiddenMenuItems];
}

function save() {
    form.patch(route('settings.menu.update'));
}
</script>

<template>
    <Head title="Personalizar menu" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-4xl">
            <header class="border-b border-slate-200 pb-6">
                <nav class="mb-2 flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-400">
                    <span>Configurações</span><span>/</span><span class="text-slate-600">Personalizar menu</span>
                </nav>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Personalizar menu</h1>
                <p class="mt-1 text-sm text-slate-500">Escolha quais funcionalidades autorizadas você quer ver na navegação.</p>
            </header>

            <form class="mt-8 space-y-6" @submit.prevent="save">
                <section
                    v-for="group in menuGroups"
                    :key="group.label"
                    class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
                >
                    <h2 class="border-b border-slate-100 px-5 py-4 text-sm font-semibold text-slate-800 sm:px-6">
                        {{ group.label }}
                    </h2>
                    <div class="divide-y divide-slate-100">
                        <label
                            v-for="item in group.items"
                            :key="item.key"
                            class="flex cursor-pointer items-center justify-between gap-4 px-5 py-4 transition hover:bg-slate-50 sm:px-6"
                            :class="item.level > 0 ? 'pl-9 sm:pl-10' : ''"
                        >
                            <span>
                                <span class="block text-sm font-medium text-slate-800">{{ item.label }}</span>
                                <span class="mt-1 block text-xs leading-5 text-slate-500">{{ item.description }}</span>
                            </span>
                            <input
                                type="checkbox"
                                class="h-4 w-4 shrink-0 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                :checked="!form.hidden_menu_items.includes(item.key)"
                                :aria-label="`Mostrar ${item.label} no menu`"
                                @change="toggleMenuItem(item.key, $event.target.checked)"
                            />
                        </label>
                    </div>
                </section>

                <div v-if="!hasMenuItems" class="rounded-2xl border border-slate-200 bg-white p-6 text-sm text-slate-500">
                    Não há funcionalidades disponíveis para personalizar nesta conta.
                </div>

                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 disabled:cursor-wait disabled:opacity-60"
                    >
                        {{ form.processing ? 'Salvando…' : 'Salvar personalização' }}
                    </button>
                </div>

                <p class="rounded-xl border border-indigo-100 bg-indigo-50/70 p-4 text-xs leading-5 text-indigo-900">
                    Esta opção altera apenas o que aparece no seu menu. As permissões de acesso continuam sendo controladas separadamente nas configurações da conta principal.
                </p>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
