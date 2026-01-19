<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';

import { Button } from '@/components/ui/button';
import { useGameState, type GameResult } from '@/composables/useGameState';
import { useKidSettings } from '@/composables/useKidSettings';
import { useLanguage } from '@/composables/useLanguage';
import KidLayout from '@/layouts/KidLayout.vue';

const props = defineProps<{ lang?: string }>();

const { t, route, initFromProp } = useLanguage();
const { kidName } = useKidSettings();
const { getLastResult, getHistory } = useGameState();

const lastResult = ref<GameResult | null>(null);

onMounted(() => {
    initFromProp(props.lang);
    lastResult.value = getLastResult();
});

const score = computed(() => lastResult.value?.score ?? 0);
const threshold = computed(() => lastResult.value?.threshold ?? 10);
const duration = computed(() => lastResult.value?.duration ?? 30);
const passed = computed(() => lastResult.value?.passed ?? false);
const history = computed(() => getHistory());

const playAgain = () => {
    router.visit(route('/play/setup'));
};

const goHome = () => {
    router.visit('/');
};
</script>

<template>
    <Head :title="t({ en: 'Results', nl: 'Resultaten', fr: 'Résultats' })" />

    <KidLayout>
        <div class="w-full max-w-lg space-y-8 text-center">
            <div v-if="passed" class="space-y-4">
                <span class="animate-bounce-subtle text-8xl">🎉</span>
                <h1 class="text-4xl font-bold">
                    <span class="bg-gradient-to-r from-green-400 via-emerald-500 to-teal-500 bg-clip-text text-transparent">
                        {{
                            t({
                                en: `Great job, ${kidName || 'friend'}!`,
                                nl: `Geweldig, ${kidName || 'vriend'}!`,
                                fr: `Bravo, ${kidName || 'ami'}!`,
                            })
                        }}
                    </span>
                </h1>
                <p class="text-xl text-sky-600 dark:text-sky-300">⭐ ⭐ ⭐</p>
            </div>
            <div v-else class="space-y-4">
                <span class="text-8xl">💪</span>
                <h1 class="text-4xl font-bold">
                    <span class="bg-gradient-to-r from-orange-400 via-amber-500 to-yellow-500 bg-clip-text text-transparent">
                        {{
                            t({
                                en: `Good try, ${kidName || 'friend'}!`,
                                nl: `Goed geprobeerd, ${kidName || 'vriend'}!`,
                                fr: `Bien essayé, ${kidName || 'ami'}!`,
                            })
                        }}
                    </span>
                </h1>
                <p class="text-lg text-sky-600 dark:text-sky-300">
                    {{ t({ en: 'Keep practicing!', nl: 'Blijf oefenen!', fr: 'Continue à t\'entraîner!' }) }} 📚
                </p>
            </div>

            <div class="card-playful">
                <div class="space-y-4">
                    <div>
                        <p class="text-lg font-medium text-sky-600 dark:text-sky-300">
                            {{ t({ en: 'Your score', nl: 'Je score', fr: 'Ton score' }) }}
                        </p>
                        <p class="bg-gradient-to-r from-sky-500 via-blue-500 to-cyan-500 bg-clip-text text-7xl font-bold text-transparent">
                            {{ score }}
                        </p>
                    </div>
                    <div class="flex items-center justify-center gap-2 text-lg">
                        <span class="text-sky-600 dark:text-sky-300">
                            🎯 {{ t({ en: 'Goal', nl: 'Doel', fr: 'Objectif' }) }}:
                        </span>
                        <span
                            class="font-bold"
                            :class="passed ? 'text-green-500' : 'text-orange-500'"
                        >
                            {{ threshold }}
                        </span>
                        <span class="text-sky-600 dark:text-sky-300">
                            ⏱️ {{ duration }}s
                        </span>
                    </div>
                </div>
            </div>

            <div v-if="history.length > 1" class="space-y-2">
                <h2 class="text-lg font-bold text-sky-700 dark:text-sky-300">
                    {{ t({ en: 'Recent games', nl: 'Recente spellen', fr: 'Parties récentes' }) }} 📊
                </h2>
                <div class="flex flex-wrap justify-center gap-2">
                    <span
                        v-for="(game, i) in history.slice(0, 5)"
                        :key="i"
                        class="rounded-xl px-3 py-1 text-sm font-bold shadow-sm"
                        :class="
                            game.passed
                                ? 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 dark:from-green-900/50 dark:to-emerald-900/50 dark:text-green-300'
                                : 'bg-gradient-to-r from-orange-100 to-amber-100 text-orange-700 dark:from-orange-900/50 dark:to-amber-900/50 dark:text-orange-300'
                        "
                    >
                        {{ game.score }}/{{ game.threshold }}
                    </span>
                </div>
            </div>

            <div class="flex flex-col gap-4 pt-4 sm:flex-row sm:justify-center">
                <Button
                    size="lg"
                    class="h-14 rounded-2xl bg-gradient-to-r from-green-400 via-emerald-500 to-teal-500 px-8 text-xl font-bold shadow-lg transition-all duration-200 hover:scale-105 hover:-translate-y-1"
                    @click="playAgain"
                >
                    {{ t({ en: 'Play again', nl: 'Opnieuw spelen', fr: 'Rejouer' }) }} 🔄
                </Button>
                <Button
                    size="lg"
                    variant="outline"
                    class="h-14 rounded-2xl border-2 border-sky-300 px-8 text-xl font-bold text-sky-700 transition-all duration-200 hover:scale-105 hover:border-sky-400 hover:bg-sky-50 dark:border-sky-600 dark:text-sky-300 dark:hover:bg-sky-900/30"
                    @click="goHome"
                >
                    {{ t({ en: 'Home', nl: 'Home', fr: 'Accueil' }) }} 🏠
                </Button>
            </div>
        </div>
    </KidLayout>
</template>
