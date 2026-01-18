<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { onMounted } from 'vue';

import LetterSelector from '@/components/game/LetterSelector.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useKidSettings } from '@/composables/useKidSettings';
import { useLanguage } from '@/composables/useLanguage';
import KidLayout from '@/layouts/KidLayout.vue';

const props = defineProps<{ lang?: string }>();

const { t, route, initFromProp } = useLanguage();
const { kidName, minWordLength, maxWordLength, knownLetters, setName, toggleLetter, applyPreset } =
    useKidSettings();

onMounted(() => initFromProp(props.lang));

const handleContinue = () => {
    router.visit(route('/play/setup'));
};
</script>

<template>
    <Head :title="t({ en: 'Settings', nl: 'Instellingen' })" />

    <KidLayout>
        <div class="w-full max-w-2xl space-y-8">
            <h1 class="text-center text-3xl font-bold text-sky-800 sm:text-4xl dark:text-sky-100">
                {{ t({ en: "What's your name?", nl: 'Hoe heet je?' }) }}
            </h1>

            <div class="space-y-2">
                <Input
                    id="name"
                    v-model="kidName"
                    type="text"
                    :placeholder="t({ en: 'Your name', nl: 'Je naam' })"
                    class="h-14 text-center text-2xl"
                    @input="setName(($event.target as HTMLInputElement).value)"
                />
            </div>

            <div class="space-y-4">
                <Label class="text-lg text-sky-800 dark:text-sky-200">
                    {{ t({ en: 'Word length', nl: 'Woordlengte' }) }}
                </Label>
                <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3">
                    <button
                        v-for="len in [2, 3, 4, 5, 6, 7, 8]"
                        :key="len"
                        type="button"
                        class="flex h-12 w-12 items-center justify-center rounded-xl text-xl font-bold transition-all sm:h-14 sm:w-14 sm:text-2xl"
                        :class="
                            len >= minWordLength && len <= maxWordLength
                                ? 'bg-sky-500 text-white'
                                : 'bg-white text-sky-800 hover:bg-sky-100 dark:bg-sky-900 dark:text-sky-200'
                        "
                        @click="
                            len < minWordLength
                                ? (minWordLength = len)
                                : len > maxWordLength
                                  ? (maxWordLength = len)
                                  : minWordLength === maxWordLength
                                    ? null
                                    : len === minWordLength
                                      ? (minWordLength = len + 1)
                                      : (maxWordLength = len - 1)
                        "
                    >
                        {{ len }}
                    </button>
                </div>
                <p class="text-center text-sky-600 dark:text-sky-400">
                    {{
                        t({
                            en: `Words with ${minWordLength}-${maxWordLength} letters`,
                            nl: `Woorden met ${minWordLength}-${maxWordLength} letters`,
                        })
                    }}
                </p>
            </div>

            <div class="space-y-4">
                <Label class="text-lg text-sky-800 dark:text-sky-200">
                    {{ t({ en: 'Letters you know', nl: 'Letters die je kent' }) }}
                </Label>
                <LetterSelector
                    :known-letters="knownLetters"
                    @toggle="toggleLetter"
                    @preset="applyPreset"
                />
            </div>

            <div class="flex justify-center pt-4">
                <Button
                    size="lg"
                    class="h-14 px-12 text-xl"
                    :disabled="!kidName || knownLetters.length < 3"
                    @click="handleContinue"
                >
                    {{ t({ en: 'Continue', nl: 'Doorgaan' }) }}
                </Button>
            </div>
        </div>
    </KidLayout>
</template>
