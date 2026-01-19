<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { onMounted } from 'vue';

import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { useGameState } from '@/composables/useGameState';
import { useKidSettings } from '@/composables/useKidSettings';
import { useLanguage } from '@/composables/useLanguage';
import KidLayout from '@/layouts/KidLayout.vue';

const props = defineProps<{ lang?: string }>();

const { t, route, initFromProp } = useLanguage();
const { kidName } = useKidSettings();
const { duration, threshold, setDuration, setThreshold } = useGameState();

onMounted(() => initFromProp(props.lang));

const durations = [30, 60, 120, 180];
const thresholds = [10, 25, 50, 100];

const startGame = () => {
    router.visit(route('/play/game'));
};
</script>

<template>
    <Head :title="t({ en: 'Game Setup', nl: 'Spel instellen', fr: 'Configuration du jeu' })" />

    <KidLayout>
        <div class="w-full max-w-lg space-y-8">
            <h1 class="text-center text-3xl font-bold sm:text-4xl">
                <span class="bg-gradient-to-r from-sky-500 via-blue-500 to-cyan-500 bg-clip-text text-transparent">
                    {{
                        t({
                            en: `Ready, ${kidName || 'friend'}?`,
                            nl: `Klaar, ${kidName || 'vriend'}?`,
                            fr: `Prêt, ${kidName || 'ami'}?`,
                        })
                    }}
                </span>
                <span class="ml-2">🎮</span>
            </h1>

            <div class="space-y-3">
                <Label class="text-lg font-bold text-sky-700 dark:text-sky-300">
                    {{ t({ en: 'Time (seconds)', nl: 'Tijd (seconden)', fr: 'Temps (secondes)' }) }} ⏱️
                </Label>
                <div class="flex justify-center">
                    <input
                        type="number"
                        :value="duration"
                        min="10"
                        max="600"
                        class="h-20 w-32 rounded-2xl border-4 border-sky-400 bg-white/90 text-center text-4xl font-bold text-sky-700 shadow-lg focus:border-sky-500 focus:outline-none dark:border-sky-600 dark:bg-sky-900/50 dark:text-sky-200"
                        @input="setDuration(Number(($event.target as HTMLInputElement).value))"
                    />
                </div>
                <div class="flex flex-wrap justify-center gap-2">
                    <button
                        v-for="d in durations"
                        :key="d"
                        type="button"
                        class="flex h-10 w-14 items-center justify-center rounded-xl text-sm font-bold shadow-md transition-all duration-200 hover:scale-105"
                        :class="
                            duration === d
                                ? 'bg-gradient-to-br from-sky-400 to-blue-500 text-white shadow-lg'
                                : 'bg-white/80 text-sky-700 hover:bg-sky-100 dark:bg-sky-900/50 dark:text-sky-200'
                        "
                        @click="setDuration(d)"
                    >
                        {{ d }}
                    </button>
                </div>
            </div>

            <div class="space-y-3">
                <Label class="text-lg font-bold text-sky-700 dark:text-sky-300">
                    {{ t({ en: 'Goal (words)', nl: 'Doel (woorden)', fr: 'Objectif (mots)' }) }} 🎯
                </Label>
                <div class="flex justify-center">
                    <input
                        type="number"
                        :value="threshold"
                        min="1"
                        max="500"
                        class="h-20 w-32 rounded-2xl border-4 border-green-400 bg-white/90 text-center text-4xl font-bold text-sky-700 shadow-lg focus:border-green-500 focus:outline-none dark:border-green-600 dark:bg-sky-900/50 dark:text-sky-200"
                        @input="setThreshold(Number(($event.target as HTMLInputElement).value))"
                    />
                </div>
                <div class="flex flex-wrap justify-center gap-2">
                    <button
                        v-for="th in thresholds"
                        :key="th"
                        type="button"
                        class="flex h-10 w-14 items-center justify-center rounded-xl text-sm font-bold shadow-md transition-all duration-200 hover:scale-105"
                        :class="
                            threshold === th
                                ? 'bg-gradient-to-br from-green-400 to-emerald-500 text-white shadow-lg'
                                : 'bg-white/80 text-sky-700 hover:bg-green-100 dark:bg-sky-900/50 dark:text-sky-200'
                        "
                        @click="setThreshold(th)"
                    >
                        {{ th }}
                    </button>
                </div>
            </div>

            <div class="flex justify-center pt-8">
                <Button
                    size="lg"
                    class="h-16 rounded-2xl bg-gradient-to-r from-green-400 via-emerald-500 to-teal-500 px-16 text-2xl font-bold shadow-xl transition-all duration-200 hover:scale-105 hover:-translate-y-1 hover:shadow-2xl"
                    @click="startGame"
                >
                    {{ t({ en: 'START!', nl: 'START!', fr: 'COMMENCER!' }) }} 🚀
                </Button>
            </div>
        </div>
    </KidLayout>
</template>
