<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';

import LanguageButton from '@/components/game/LanguageButton.vue';
import { useLanguage, type Language } from '@/composables/useLanguage';
import KidLayout from '@/layouts/KidLayout.vue';

const { language, setLanguage, t } = useLanguage();

const selectLanguage = (lang: Language) => {
    setLanguage(lang);
    router.visit('/kid-settings');
};
</script>

<template>
    <Head :title="t({ en: 'Welcome', nl: 'Welkom' })" />

    <KidLayout>
        <div class="w-full max-w-lg space-y-8 text-center">
            <h1 class="text-4xl font-bold text-sky-800 sm:text-5xl dark:text-sky-100">
                {{ t({ en: 'Welcome to LeesSpurt!', nl: 'Welkom bij LeesSpurt!' }) }}
            </h1>

            <p class="text-xl text-sky-700 dark:text-sky-300">
                {{ t({ en: 'Choose your language', nl: 'Kies je taal' }) }}
            </p>

            <div class="flex justify-center gap-6">
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
            </div>
        </div>
    </KidLayout>
</template>
