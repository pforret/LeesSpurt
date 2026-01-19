<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';

import LanguageButton from '@/components/game/LanguageButton.vue';
import { useLanguage, type Language } from '@/composables/useLanguage';
import KidLayout from '@/layouts/KidLayout.vue';

const { language, setLanguage, t } = useLanguage();

const selectLanguage = (lang: Language) => {
    setLanguage(lang);
    router.visit(`/${lang}/settings`);
};
</script>

<template>
    <Head :title="t({ en: 'Welcome', nl: 'Welkom', fr: 'Bienvenue' })" />

    <KidLayout>
        <div class="w-full max-w-lg space-y-8 text-center">
            <div class="space-y-2">
                <div class="text-5xl sm:text-6xl">🎉</div>
                <h1 class="font-game text-4xl font-bold sm:text-5xl">
                    <span class="bg-gradient-to-r from-sky-500 via-blue-500 to-cyan-500 bg-clip-text text-transparent">
                        {{ t({ en: 'Welcome to Letterly!', nl: 'Welkom bij Letterly!', fr: 'Bienvenue à Letterly!' }) }}
                    </span>
                </h1>
            </div>

            <p class="text-xl font-medium text-sky-600 dark:text-sky-300">
                {{ t({ en: 'Choose your language', nl: 'Kies je taal', fr: 'Choisis ta langue' }) }}
            </p>

            <div class="flex flex-wrap justify-center gap-6">
                <LanguageButton
                    language="en"
                    :selected="language === 'en'"
                    @select="selectLanguage"
                />
                <LanguageButton
                    language="nl"
                    :selected="language === 'nl'"
                    @select="selectLanguage"
                />
                <LanguageButton
                    language="fr"
                    :selected="language === 'fr'"
                    @select="selectLanguage"
                />
            </div>

            <p class="text-lg text-sky-600 dark:text-sky-400">
                {{ t({ en: "Let's learn to read!", nl: 'Leren lezen is leuk!', fr: 'Apprendre à lire, c\'est fun!' }) }} 📖✨
            </p>
        </div>
    </KidLayout>
</template>
