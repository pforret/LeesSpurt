<script setup lang="ts">
import { computed } from 'vue';

import { LETTER_PRESETS } from '@/composables/useKidSettings';
import { useLanguage } from '@/composables/useLanguage';

const props = defineProps<{
    knownLetters: string;
}>();

const emit = defineEmits<{
    toggle: [letter: string];
    preset: [letters: string];
}>();

const { language, t } = useLanguage();

const alphabet = 'abcdefghijklmnopqrstuvwxyz'.split('');

const presets = computed(() => {
    const lang = language.value;
    return [
        {
            label: t({ en: 'Vowels', nl: 'Klinkers' }),
            letters: LETTER_PRESETS[lang].vowels,
        },
        {
            label: t({ en: 'Beginners', nl: 'Beginners' }),
            letters: LETTER_PRESETS[lang].beginners,
        },
        {
            label: t({ en: 'Intermediate', nl: 'Gevorderd' }),
            letters: LETTER_PRESETS[lang].intermediate,
        },
    ];
});

const isSelected = (letter: string) => props.knownLetters.includes(letter.toLowerCase());
</script>

<template>
    <div class="space-y-4">
        <div class="flex flex-wrap gap-2">
            <button
                v-for="preset in presets"
                :key="preset.label"
                type="button"
                class="rounded-lg bg-sky-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-sky-600"
                @click="emit('preset', preset.letters)"
            >
                {{ preset.label }}
            </button>
        </div>

        <div class="grid grid-cols-9 gap-1 sm:gap-2">
            <button
                v-for="letter in alphabet"
                :key="letter"
                type="button"
                class="flex h-10 w-10 items-center justify-center rounded-lg text-lg font-bold transition-all sm:h-12 sm:w-12"
                :class="
                    isSelected(letter)
                        ? 'bg-sky-500 text-white shadow-md'
                        : 'bg-white text-sky-800 hover:bg-sky-100 dark:bg-sky-900 dark:text-sky-200'
                "
                @click="emit('toggle', letter)"
            >
                {{ letter }}
            </button>
        </div>
    </div>
</template>
