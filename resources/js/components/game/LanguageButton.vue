<script setup lang="ts">
import type { Language } from '@/composables/useLanguage';

defineProps<{
    language: Language;
    selected: boolean;
}>();

defineEmits<{
    select: [language: Language];
}>();

const flags: Record<Language, string> = {
    en: '🇬🇧',
    nl: '🇳🇱',
    fr: '🇫🇷',
};

const labels: Record<Language, string> = {
    en: 'English',
    nl: 'Nederlands',
    fr: 'Français',
};
</script>

<template>
    <button
        type="button"
        class="group flex flex-col items-center gap-3 rounded-3xl border-4 p-6 shadow-lg transition-all duration-200 hover:scale-105 hover:-translate-y-1 hover:shadow-xl active:scale-95"
        :class="
            selected
                ? 'border-sky-400 bg-gradient-to-br from-sky-50 to-cyan-50 shadow-xl dark:from-sky-900/50 dark:to-cyan-900/50'
                : 'border-transparent bg-white/80 hover:border-sky-300 dark:bg-sky-900/80 dark:hover:border-sky-500'
        "
        @click="$emit('select', language)"
    >
        <span class="text-6xl transition-transform duration-200 group-hover:scale-110">{{ flags[language] }}</span>
        <span class="text-lg font-bold text-sky-700 dark:text-sky-200">
            {{ labels[language] }}
        </span>
        <span v-if="selected" class="text-sm text-sky-500">✓</span>
    </button>
</template>
