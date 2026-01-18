<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { computed } from 'vue';

import { Button } from '@/components/ui/button';
import { useGameState } from '@/composables/useGameState';
import { useKidSettings } from '@/composables/useKidSettings';
import { useLanguage } from '@/composables/useLanguage';
import KidLayout from '@/layouts/KidLayout.vue';

const { t } = useLanguage();
const { kidName } = useKidSettings();
const { score, threshold, duration, passed, getHistory } = useGameState();

const history = computed(() => getHistory());

const playAgain = () => {
    router.visit('/play/setup');
};

const goHome = () => {
    router.visit('/');
};
</script>

<template>
    <Head :title="t({ en: 'Results', nl: 'Resultaten' })" />

    <KidLayout>
        <div class="w-full max-w-lg space-y-8 text-center">
            <div v-if="passed" class="space-y-4">
                <span class="text-8xl">🎉</span>
                <h1 class="text-4xl font-bold text-green-600 dark:text-green-400">
                    {{
                        t({
                            en: `Great job, ${kidName || 'friend'}!`,
                            nl: `Geweldig, ${kidName || 'vriend'}!`,
                        })
                    }}
                </h1>
            </div>
            <div v-else class="space-y-4">
                <span class="text-8xl">💪</span>
                <h1 class="text-4xl font-bold text-sky-700 dark:text-sky-300">
                    {{
                        t({
                            en: `Good try, ${kidName || 'friend'}!`,
                            nl: `Goed geprobeerd, ${kidName || 'vriend'}!`,
                        })
                    }}
                </h1>
            </div>

            <div
                class="rounded-2xl bg-white p-8 shadow-lg dark:bg-sky-900"
            >
                <div class="space-y-4">
                    <div>
                        <p class="text-lg text-sky-600 dark:text-sky-400">
                            {{ t({ en: 'Your score', nl: 'Je score' }) }}
                        </p>
                        <p class="text-6xl font-bold text-sky-800 dark:text-sky-100">
                            {{ score }}
                        </p>
                    </div>
                    <div class="flex items-center justify-center gap-2">
                        <span class="text-sky-600 dark:text-sky-400">
                            {{ t({ en: 'Goal', nl: 'Doel' }) }}:
                        </span>
                        <span
                            class="font-bold"
                            :class="passed ? 'text-green-500' : 'text-orange-500'"
                        >
                            {{ threshold }}
                        </span>
                        <span class="text-sky-600 dark:text-sky-400">
                            {{ t({ en: 'in', nl: 'in' }) }} {{ duration }}s
                        </span>
                    </div>
                </div>
            </div>

            <div v-if="history.length > 1" class="space-y-2">
                <h2 class="text-lg font-medium text-sky-700 dark:text-sky-300">
                    {{ t({ en: 'Recent games', nl: 'Recente spellen' }) }}
                </h2>
                <div class="flex flex-wrap justify-center gap-2">
                    <span
                        v-for="(game, i) in history.slice(0, 5)"
                        :key="i"
                        class="rounded-lg px-3 py-1 text-sm font-medium"
                        :class="
                            game.passed
                                ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300'
                                : 'bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-300'
                        "
                    >
                        {{ game.score }}/{{ game.threshold }}
                    </span>
                </div>
            </div>

            <div class="flex flex-col gap-4 pt-4 sm:flex-row sm:justify-center">
                <Button
                    size="lg"
                    class="h-14 px-8 text-xl"
                    @click="playAgain"
                >
                    {{ t({ en: 'Play again', nl: 'Opnieuw spelen' }) }}
                </Button>
                <Button
                    size="lg"
                    variant="outline"
                    class="h-14 px-8 text-xl"
                    @click="goHome"
                >
                    {{ t({ en: 'Home', nl: 'Home' }) }}
                </Button>
            </div>
        </div>
    </KidLayout>
</template>
