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
        class="flex flex-col items-center gap-2 rounded-2xl border-4 p-6 transition-all"
        :class="
            selected
                ? 'border-sky-500 bg-sky-100 dark:bg-sky-800'
                : 'border-transparent bg-white hover:border-sky-300 dark:bg-sky-900 dark:hover:border-sky-600'
        "
        @click="$emit('select', language)"
    >
        <span class="text-6xl">{{ flags[language] }}</span>
        <span class="text-lg font-medium text-sky-800 dark:text-sky-200">
            {{ labels[language] }}
        </span>
    </button>
</template>
