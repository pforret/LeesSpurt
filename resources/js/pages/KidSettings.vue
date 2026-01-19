<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { onMounted } from 'vue';

import LetterSelector from '@/components/game/LetterSelector.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { useKidSettings } from '@/composables/useKidSettings';
import { useLanguage } from '@/composables/useLanguage';
import KidLayout from '@/layouts/KidLayout.vue';

const props = defineProps<{ lang?: string }>();

const { t, route, initFromProp } = useLanguage();
const { kidName, kidAge, minWordLength, maxWordLength, knownLetters, setName, setAge, toggleLetter, applyPreset } =
    useKidSettings();

const ages = [5, 6, 7, 8, 9, 10];

onMounted(() => initFromProp(props.lang));

const handleContinue = () => {
    router.visit(route('/play/setup'));
};
</script>

<template>
    <Head :title="t({ en: 'Settings', nl: 'Instellingen', fr: 'Paramètres' })" />

    <KidLayout>
        <div class="w-full max-w-2xl space-y-8">
            <h1 class="text-center text-3xl font-bold text-sky-800 sm:text-4xl dark:text-sky-100">
                {{ t({ en: "What's your name?", nl: 'Hoe heet je?', fr: 'Comment tu t\'appelles?' }) }}
            </h1>

            <div class="space-y-2">
                <input
                    id="name"
                    v-model="kidName"
                    type="text"
                    :placeholder="t({ en: 'Your name', nl: 'Je naam', fr: 'Ton nom' })"
                    class="w-full rounded-xl border-2 border-sky-300 bg-white px-4 py-4 text-center font-game text-4xl text-sky-800 placeholder:text-sky-300 focus:border-sky-500 focus:outline-none sm:text-5xl dark:border-sky-700 dark:bg-sky-900 dark:text-sky-100 dark:placeholder:text-sky-600"
                    @input="setName(($event.target as HTMLInputElement).value)"
                />
            </div>

            <div class="space-y-4">
                <Label class="text-lg text-sky-800 dark:text-sky-200">
                    {{ t({ en: 'How old are you?', nl: 'Hoe oud ben je?', fr: 'Quel âge as-tu?' }) }}
                </Label>
                <p class="text-center text-sm text-sky-500 dark:text-sky-400">
                    {{ t({ en: 'Younger = simpler, more common words', nl: 'Jonger = eenvoudigere, frequentere woorden', fr: 'Plus jeune = mots plus simples et courants' }) }}
                </p>
                <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3">
                    <button
                        v-for="age in ages"
                        :key="age"
                        type="button"
                        class="flex h-12 w-12 items-center justify-center rounded-xl text-xl font-bold transition-all sm:h-14 sm:w-14 sm:text-2xl"
                        :class="
                            kidAge === age
                                ? 'bg-amber-500 text-white shadow-lg'
                                : 'bg-white text-sky-800 hover:bg-sky-100 dark:bg-sky-900 dark:text-sky-200'
                        "
                        @click="setAge(age)"
                    >
                        {{ age }}
                    </button>
                </div>
            </div>

            <div class="space-y-4">
                <Label class="text-lg text-sky-800 dark:text-sky-200">
                    {{ t({ en: 'Word length', nl: 'Woordlengte', fr: 'Longueur des mots' }) }}
                </Label>
                <p class="text-center text-sm text-sky-500 dark:text-sky-400">
                    {{ t({ en: 'Select the range of word lengths to practice', nl: 'Selecteer het bereik van woordlengtes om te oefenen', fr: 'Sélectionne la plage de longueurs de mots' }) }}
                </p>
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
                            fr: `Mots de ${minWordLength}-${maxWordLength} lettres`,
                        })
                    }}
                </p>
            </div>

            <div class="space-y-4">
                <Label class="text-lg text-sky-800 dark:text-sky-200">
                    {{ t({ en: 'Letters you know', nl: 'Letters die je kent', fr: 'Lettres que tu connais' }) }}
                </Label>
                <p class="text-center text-sm text-sky-500 dark:text-sky-400">
                    {{ t({ en: 'Only words with these letters will be shown', nl: 'Alleen woorden met deze letters worden getoond', fr: 'Seuls les mots avec ces lettres seront montrés' }) }}
                </p>
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
                    {{ t({ en: 'Continue', nl: 'Doorgaan', fr: 'Continuer' }) }}
                </Button>
            </div>
        </div>
    </KidLayout>
</template>
