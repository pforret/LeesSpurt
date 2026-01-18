import { ref } from 'vue';

import type { Language } from './useLanguage';

const wordsCache = ref<Record<string, string[]>>({});

export function useThesaurus() {
    const loading = ref(false);
    const error = ref<string | null>(null);

    const loadWords = async (language: Language, length: number): Promise<string[]> => {
        const key = `${language}-${length}`;
        if (wordsCache.value[key]) {
            return wordsCache.value[key];
        }

        try {
            loading.value = true;
            const response = await fetch(`/api/words/${language}/${length}`);
            if (!response.ok) {
                throw new Error(`Failed to load words for ${language} length ${length}`);
            }
            const words = await response.json();
            wordsCache.value[key] = words;
            return words;
        } catch (e) {
            error.value = e instanceof Error ? e.message : 'Failed to load words';
            return [];
        } finally {
            loading.value = false;
        }
    };

    const loadWordsInRange = async (
        language: Language,
        minLength: number,
        maxLength: number
    ): Promise<string[]> => {
        const allWords: string[] = [];
        for (let len = minLength; len <= maxLength; len++) {
            const words = await loadWords(language, len);
            allWords.push(...words);
        }
        return allWords;
    };

    const filterByLetters = (words: string[], knownLetters: string): string[] => {
        const letters = new Set(knownLetters.toLowerCase().split(''));
        return words.filter((word) =>
            word.toLowerCase().split('').every((char) => letters.has(char))
        );
    };

    const selectRandomWord = (words: string[], exclude?: string): string | null => {
        if (words.length === 0) return null;
        if (words.length === 1) return words[0];

        const available = exclude ? words.filter((w) => w !== exclude) : words;
        if (available.length === 0) return words[0];

        return available[Math.floor(Math.random() * available.length)];
    };

    return {
        loading,
        error,
        loadWords,
        loadWordsInRange,
        filterByLetters,
        selectRandomWord,
    };
}
