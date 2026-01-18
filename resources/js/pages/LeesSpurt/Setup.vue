<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';

import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { useGameState } from '@/composables/useGameState';
import { useKidSettings } from '@/composables/useKidSettings';
import { useLanguage } from '@/composables/useLanguage';
import KidLayout from '@/layouts/KidLayout.vue';

const { t } = useLanguage();
const { kidName } = useKidSettings();
const { duration, threshold, setDuration, setThreshold } = useGameState();

const durations = [15, 30, 60];
const thresholds = [5, 10, 15, 20];

const startGame = () => {
    router.visit('/play/game');
};
</script>

<template>
    <Head :title="t({ en: 'Game Setup', nl: 'Spel instellen' })" />

    <KidLayout>
        <div class="w-full max-w-lg space-y-8">
            <h1 class="text-center text-3xl font-bold text-sky-800 sm:text-4xl dark:text-sky-100">
                {{
                    t({
                        en: `Ready, ${kidName || 'friend'}?`,
                        nl: `Klaar, ${kidName || 'vriend'}?`,
                    })
                }}
            </h1>

            <div class="space-y-4">
                <Label class="text-lg text-sky-800 dark:text-sky-200">
                    {{ t({ en: 'Time (seconds)', nl: 'Tijd (seconden)' }) }}
                </Label>
                <div class="flex justify-center gap-4">
                    <button
                        v-for="d in durations"
                        :key="d"
                        type="button"
                        class="flex h-16 w-20 items-center justify-center rounded-xl text-2xl font-bold transition-all"
                        :class="
                            duration === d
                                ? 'bg-sky-500 text-white shadow-lg'
                                : 'bg-white text-sky-800 hover:bg-sky-100 dark:bg-sky-900 dark:text-sky-200'
                        "
                        @click="setDuration(d)"
                    >
                        {{ d }}
                    </button>
                </div>
            </div>

            <div class="space-y-4">
                <Label class="text-lg text-sky-800 dark:text-sky-200">
                    {{ t({ en: 'Goal (words)', nl: 'Doel (woorden)' }) }}
                </Label>
                <div class="flex justify-center gap-4">
                    <button
                        v-for="th in thresholds"
                        :key="th"
                        type="button"
                        class="flex h-16 w-16 items-center justify-center rounded-xl text-2xl font-bold transition-all"
                        :class="
                            threshold === th
                                ? 'bg-green-500 text-white shadow-lg'
                                : 'bg-white text-sky-800 hover:bg-sky-100 dark:bg-sky-900 dark:text-sky-200'
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
                    class="h-16 px-16 text-2xl font-bold"
                    @click="startGame"
                >
                    {{ t({ en: 'START!', nl: 'START!' }) }}
                </Button>
            </div>
        </div>
    </KidLayout>
</template>
