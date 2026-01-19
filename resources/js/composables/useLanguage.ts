import { ref, watch, computed } from 'vue';

export type Language = 'en' | 'nl' | 'fr';

const STORAGE_KEY = 'letterly.language';

const language = ref<Language>((localStorage.getItem(STORAGE_KEY) as Language) || 'en');

watch(language, (val) => {
    localStorage.setItem(STORAGE_KEY, val);
});

export function useLanguage() {
    const setLanguage = (lang: Language) => {
        language.value = lang;
    };

    const initFromProp = (lang?: string) => {
        if (lang === 'en' || lang === 'nl' || lang === 'fr') {
            language.value = lang;
        }
    };

    const t = (translations: Record<Language, string>) => {
        return translations[language.value];
    };

    const langPath = computed(() => `/${language.value}`);

    const route = (path: string) => `/${language.value}${path}`;

    return {
        language,
        setLanguage,
        initFromProp,
        t,
        langPath,
        route,
    };
}
