import { ref, watch } from 'vue';

export type Language = 'en' | 'nl';

const STORAGE_KEY = 'leesSpurt.language';

const language = ref<Language>((localStorage.getItem(STORAGE_KEY) as Language) || 'en');

watch(language, (val) => {
    localStorage.setItem(STORAGE_KEY, val);
});

export function useLanguage() {
    const setLanguage = (lang: Language) => {
        language.value = lang;
    };

    const t = (translations: Record<Language, string>) => {
        return translations[language.value];
    };

    return {
        language,
        setLanguage,
        t,
    };
}
