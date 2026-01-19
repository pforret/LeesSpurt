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
            label: t({ en: 'Vowels', nl: 'Klinkers', fr: 'Voyelles' }),
            letters: LETTER_PRESETS[lang].vowels,
        },
        {
            label: t({ en: 'Beginners', nl: 'Beginners', fr: 'Débutants' }),
            letters: LETTER_PRESETS[lang].beginners,
        },
        {
            label: t({ en: 'Intermediate', nl: 'Gevorderd', fr: 'Intermédiaire' }),
            letters: LETTER_PRESETS[lang].intermediate,
        },
    ];
});

const isSelected = (letter: string) => props.knownLetters.includes(letter.toLowerCase());
</script>

<template>
    <div class="space-y-4">
        <div class="flex flex-wrap justify-center gap-2">
            <button
                v-for="preset in presets"
                :key="preset.label"
                type="button"
                class="rounded-xl bg-gradient-to-r from-sky-500 to-blue-500 px-4 py-2 text-sm font-bold text-white shadow-md transition-all duration-200 hover:scale-105 hover:-translate-y-0.5 hover:shadow-lg"
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
                class="flex h-10 w-10 items-center justify-center rounded-xl text-lg font-bold shadow-sm transition-all duration-200 hover:scale-110 sm:h-12 sm:w-12"
                :class="
                    isSelected(letter)
                        ? 'bg-gradient-to-br from-green-400 to-emerald-500 text-white shadow-md'
                        : 'bg-white/80 text-sky-700 hover:bg-sky-100 dark:bg-sky-900/50 dark:text-sky-200'
                "
                @click="emit('toggle', letter)"
            >
                {{ letter }}
            </button>
        </div>
    </div>
</template>
