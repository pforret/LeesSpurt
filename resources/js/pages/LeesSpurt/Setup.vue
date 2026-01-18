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
            <h1 class="text-center text-3xl font-bold text-sky-800 sm:text-4xl dark:text-sky-100">
                {{
                    t({
                        en: `Ready, ${kidName || 'friend'}?`,
                        nl: `Klaar, ${kidName || 'vriend'}?`,
                        fr: `Prêt, ${kidName || 'ami'}?`,
                    })
                }}
            </h1>

            <div class="space-y-4">
                <Label class="text-lg text-sky-800 dark:text-sky-200">
                    {{ t({ en: 'Time (seconds)', nl: 'Tijd (seconden)', fr: 'Temps (secondes)' }) }}
                </Label>
                <div class="flex flex-wrap justify-center gap-3">
                    <button
                        v-for="d in durations"
                        :key="d"
                        type="button"
                        class="flex h-14 w-16 items-center justify-center rounded-xl text-xl font-bold transition-all"
                        :class="
                            duration === d
                                ? 'bg-sky-500 text-white shadow-lg'
                                : 'bg-white text-sky-800 hover:bg-sky-100 dark:bg-sky-900 dark:text-sky-200'
                        "
                        @click="setDuration(d)"
                    >
                        {{ d }}
                    </button>
                    <input
                        type="number"
                        :value="duration"
                        min="10"
                        max="600"
                        class="h-14 w-20 rounded-xl border-2 border-sky-300 bg-white text-center text-xl font-bold text-sky-800 focus:border-sky-500 focus:outline-none dark:border-sky-700 dark:bg-sky-900 dark:text-sky-200"
                        @input="setDuration(Number(($event.target as HTMLInputElement).value))"
                    />
                </div>
            </div>

            <div class="space-y-4">
                <Label class="text-lg text-sky-800 dark:text-sky-200">
                    {{ t({ en: 'Goal (words)', nl: 'Doel (woorden)', fr: 'Objectif (mots)' }) }}
                </Label>
                <div class="flex flex-wrap justify-center gap-3">
                    <button
                        v-for="th in thresholds"
                        :key="th"
                        type="button"
                        class="flex h-14 w-16 items-center justify-center rounded-xl text-xl font-bold transition-all"
                        :class="
                            threshold === th
                                ? 'bg-green-500 text-white shadow-lg'
                                : 'bg-white text-sky-800 hover:bg-sky-100 dark:bg-sky-900 dark:text-sky-200'
                        "
                        @click="setThreshold(th)"
                    >
                        {{ th }}
                    </button>
                    <input
                        type="number"
                        :value="threshold"
                        min="1"
                        max="500"
                        class="h-14 w-20 rounded-xl border-2 border-green-300 bg-white text-center text-xl font-bold text-sky-800 focus:border-green-500 focus:outline-none dark:border-green-700 dark:bg-sky-900 dark:text-sky-200"
                        @input="setThreshold(Number(($event.target as HTMLInputElement).value))"
                    />
                </div>
            </div>

            <div class="flex justify-center pt-8">
                <Button
                    size="lg"
                    class="h-16 px-16 text-2xl font-bold"
                    @click="startGame"
                >
                    {{ t({ en: 'START!', nl: 'START!', fr: 'COMMENCER!' }) }}
                </Button>
            </div>
        </div>
    </KidLayout>
</template>
