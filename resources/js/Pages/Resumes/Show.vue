<script setup>
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    resume: {
        type: Object,
        required: true,
    },
});

const printDocument = () => {
    window.print();
};
</script>

<template>
    <Head :title="`${resume.title}`" />

    <div class="min-h-screen bg-slate-100 py-6 sm:py-10 px-4">
        <!-- Top Sticky Action Bar (hidden when printing) -->
        <div class="max-w-4xl mx-auto mb-6 flex items-center justify-between gap-4 print:hidden">
            <Link
                :href="route('resumes.edit', resume.id)"
                class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl shadow-xs transition"
            >
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Voltar ao Editor
            </Link>

            <div class="flex items-center gap-2">
                <button
                    @click="printDocument"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl shadow-xs transition"
                >
                    <svg class="w-4 h-4 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 6 2 18 2 18 9"/>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                        <rect width="12" height="8" x="6" y="14"/>
                    </svg>
                    Imprimir
                </button>
                <a
                    :href="route('resumes.pdf', resume.id)"
                    target="_blank"
                    class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-slate-900 hover:bg-slate-800 rounded-xl shadow-xs transition"
                >
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    Baixar PDF
                </a>
            </div>
        </div>

        <!-- Document Sheet (A4 look) -->
        <article class="max-w-4xl mx-auto bg-white rounded-xl shadow-md border border-slate-200/80 p-8 sm:p-14 text-slate-800 font-sans print:shadow-none print:border-none print:p-0">
            <!-- Header -->
            <header class="border-b-2 border-slate-900 pb-5 mb-6">
                <div class="flex items-start gap-4">
                    <img v-if="resume.photo_url" :src="resume.photo_url" alt="Foto profissional" class="h-20 w-20 shrink-0 rounded-full object-cover" />
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 uppercase tracking-tight">
                        {{ resume.personal_info?.full_name || resume.title }}
                    </h1>
                </div>

                <div class="mt-2.5 flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-600">
                    <span v-if="resume.personal_info?.email">
                        <strong>E-mail:</strong> {{ resume.personal_info.email }}
                    </span>
                    <span v-if="resume.personal_info?.phone">
                        <strong>Telefone:</strong> {{ resume.personal_info.phone }}
                    </span>
                    <span v-if="resume.personal_info?.city">
                        <strong>Localização:</strong> {{ resume.personal_info.city }}
                    </span>
                    <span v-if="resume.personal_info?.linkedin">
                        <strong>LinkedIn:</strong> {{ resume.personal_info.linkedin }}
                    </span>
                    <span v-if="resume.personal_info?.github">
                        <strong>GitHub:</strong> {{ resume.personal_info.github }}
                    </span>
                    <span v-if="resume.personal_info?.website">
                        <strong>Portfólio:</strong> {{ resume.personal_info.website }}
                    </span>
                </div>
            </header>

            <!-- Resumo Profissional -->
            <section v-if="resume.summary" class="mb-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-900 border-b border-slate-200 pb-1 mb-2.5">
                    Resumo Profissional
                </h2>
                <p class="text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                    {{ resume.summary }}
                </p>
            </section>

            <!-- Experiência Profissional -->
            <section v-if="resume.experiences && resume.experiences.length > 0" class="mb-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-900 border-b border-slate-200 pb-1 mb-3">
                    Experiência Profissional
                </h2>
                <div class="space-y-4">
                    <div v-for="exp in resume.experiences" :key="exp.id" class="break-inside-avoid">
                        <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1">
                            <h3 class="text-xs sm:text-sm font-bold text-slate-900">{{ exp.position }}</h3>
                            <span class="text-xs text-slate-500 font-medium">
                                {{ exp.start_date }} — {{ exp.is_current ? 'Atual' : (exp.end_date || 'Presente') }}
                            </span>
                        </div>
                        <div class="text-xs text-slate-600 italic">
                            {{ exp.company }}{{ exp.location ? ' — ' + exp.location : '' }}
                        </div>
                        <p v-if="exp.description" class="mt-1.5 text-xs text-slate-700 leading-relaxed whitespace-pre-line">
                            {{ exp.description }}
                        </p>
                    </div>
                </div>
            </section>

            <!-- Formação Acadêmica -->
            <section v-if="resume.education && resume.education.length > 0" class="mb-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-900 border-b border-slate-200 pb-1 mb-3">
                    Formação Acadêmica
                </h2>
                <div class="space-y-4">
                    <div v-for="edu in resume.education" :key="edu.id" class="break-inside-avoid">
                        <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1">
                            <h3 class="text-xs sm:text-sm font-bold text-slate-900">{{ edu.course }}</h3>
                            <span v-if="edu.start_date || edu.end_date" class="text-xs text-slate-500 font-medium">
                                {{ edu.start_date }} — {{ edu.end_date || 'Atual' }}
                            </span>
                        </div>
                        <div class="text-xs text-slate-600 italic">
                            {{ edu.institution }}{{ edu.degree ? ' (' + edu.degree + ')' : '' }}
                        </div>
                        <p v-if="edu.description" class="mt-1.5 text-xs text-slate-700 leading-relaxed whitespace-pre-line">
                            {{ edu.description }}
                        </p>
                    </div>
                </div>
            </section>

            <!-- Habilidades -->
            <section v-if="resume.skills && resume.skills.length > 0" class="mb-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-900 border-b border-slate-200 pb-1 mb-3">
                    Habilidades
                </h2>
                <div class="flex flex-wrap gap-2">
                    <span
                        v-for="skill in resume.skills"
                        :key="skill.id"
                        class="px-2.5 py-1 text-xs rounded-md bg-slate-100 text-slate-800 font-medium border border-slate-200"
                    >
                        {{ skill.name }}{{ skill.level ? ' (' + skill.level + ')' : '' }}
                    </span>
                </div>
            </section>

            <!-- Idiomas -->
            <section v-if="resume.languages && resume.languages.length > 0" class="mb-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-900 border-b border-slate-200 pb-1 mb-3">
                    Idiomas
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                    <div v-for="lang in resume.languages" :key="lang.id">
                        <strong class="text-slate-800">{{ lang.language }}</strong> —
                        <span class="text-slate-600">{{ lang.level }}</span>
                    </div>
                </div>
            </section>

            <!-- Cursos -->
            <section v-if="resume.courses && resume.courses.length > 0" class="mb-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-900 border-b border-slate-200 pb-1 mb-3">
                    Cursos
                </h2>
                <div class="space-y-3">
                    <div v-for="course in resume.courses" :key="course.id" class="break-inside-avoid">
                        <div class="flex justify-between items-baseline">
                            <span class="text-xs sm:text-sm font-semibold text-slate-900">{{ course.name }}</span>
                            <span v-if="course.date" class="text-xs text-slate-500">{{ course.date }}</span>
                        </div>
                        <div v-if="course.institution" class="text-xs text-slate-600 italic">
                            {{ course.institution }}
                        </div>
                    </div>
                </div>
            </section>

            <!-- Certificações -->
            <section v-if="resume.certifications && resume.certifications.length > 0" class="mb-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-900 border-b border-slate-200 pb-1 mb-3">
                    Certificações
                </h2>
                <div class="space-y-3">
                    <div v-for="cert in resume.certifications" :key="cert.id" class="break-inside-avoid">
                        <div class="flex justify-between items-baseline">
                            <span class="text-xs sm:text-sm font-semibold text-slate-900">{{ cert.name }}</span>
                            <span v-if="cert.date" class="text-xs text-slate-500">{{ cert.date }}</span>
                        </div>
                        <div v-if="cert.institution" class="text-xs text-slate-600 italic">
                            {{ cert.institution }}{{ cert.code ? ' | Código: ' + cert.code : '' }}
                        </div>
                    </div>
                </div>
            </section>

            <!-- Informações Adicionais -->
            <section v-if="resume.additional_info" class="mb-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-900 border-b border-slate-200 pb-1 mb-2.5">
                    Informações Adicionais
                </h2>
                <p class="text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                    {{ resume.additional_info }}
                </p>
            </section>
        </article>
    </div>
</template>
