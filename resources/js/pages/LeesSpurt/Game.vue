<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

import CountdownOverlay from '@/components/game/CountdownOverlay.vue';
import TimerBar from '@/components/game/TimerBar.vue';
import WordDisplay from '@/components/game/WordDisplay.vue';
import { useGameState } from '@/composables/useGameState';
import { useKidSettings } from '@/composables/useKidSettings';
import { useLanguage } from '@/composables/useLanguage';
import { useThesaurus } from '@/composables/useThesaurus';
import KidLayout from '@/layouts/KidLayout.vue';

const props = defineProps<{ lang?: string }>();

const { language, t, route, initFromProp } = useLanguage();
const { minWordLength, maxWordLength, knownLetters } = useKidSettings();
const { loadWordsInRange, filterByLetters, selectRandomWord } = useThesaurus();
const {
    phase,
    score,
    timeRemaining,
    currentWord,
    countdownValue,
    progress,
    startCountdown,
    startGame,
    incrementScore,
    saveResult,
    reset,
    cleanup,
} = useGameState();

const availableWords = ref<string[]>([]);
const loading = ref(true);

const nextWord = () => {
    const word = selectRandomWord(availableWords.value, currentWord.value);
    if (word) {
        currentWord.value = word;
        incrementScore();
    }
};

const handleAdvance = () => {
    if (phase.value === 'playing') {
        nextWord();
    }
};

const handleKeydown = (e: KeyboardEvent) => {
    if (e.code === 'Space' && phase.value === 'playing') {
        e.preventDefault();
        handleAdvance();
    }
};

const finishGame = () => {
    saveResult();
    router.visit(route('/play/results'));
};

onMounted(async () => {
    initFromProp(props.lang);
    reset();
    loading.value = true;

    const words = await loadWordsInRange(language.value, minWordLength.value, maxWordLength.value);
    availableWords.value = filterByLetters(words, knownLetters.value);

    if (availableWords.value.length === 0) {
        availableWords.value = ['no', 'more'];
    }

    loading.value = false;

    startCountdown(() => {
        currentWord.value = selectRandomWord(availableWords.value) || 'read';
        startGame(
            () => {},
            () => finishGame()
        );
    });

    window.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
    cleanup();
    window.removeEventListener('keydown', handleKeydown);
});
</script>

<template>
    <Head :title="t({ en: 'Play!', nl: 'Speel!', fr: 'Jouer!' })" />

    <CountdownOverlay v-if="phase === 'countdown'" :value="countdownValue" />

    <KidLayout>
        <div
            v-if="phase === 'playing'"
            class="flex w-full max-w-2xl flex-col items-center gap-8"
            @click="handleAdvance"
        >
            <TimerBar :progress="progress" :time-remaining="timeRemaining" />

            <div class="text-center">
                <span class="text-2xl font-bold text-sky-700 dark:text-sky-300">
                    {{ t({ en: 'Score', nl: 'Score', fr: 'Score' }) }}: {{ score }}
                </span>
            </div>

            <WordDisplay :word="currentWord" />

            <p class="text-lg text-sky-600 dark:text-sky-400">
                {{ t({ en: 'Press SPACE or tap to continue', nl: 'Druk op SPATIE of tik om door te gaan', fr: 'Appuie sur ESPACE ou tape pour continuer' }) }}
            </p>
        </div>

        <div v-else-if="loading" class="text-2xl text-sky-700 dark:text-sky-300">
            {{ t({ en: 'Loading...', nl: 'Laden...', fr: 'Chargement...' }) }}
        </div>
    </KidLayout>
</template>
