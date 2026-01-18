import { ref, watch } from 'vue';

const KEYS = {
    name: 'leesSpurt.kidName',
    minLen: 'leesSpurt.minWordLength',
    maxLen: 'leesSpurt.maxWordLength',
    letters: 'leesSpurt.knownLetters',
};

const kidName = ref(localStorage.getItem(KEYS.name) || '');
const minWordLength = ref(parseInt(localStorage.getItem(KEYS.minLen) || '2', 10));
const maxWordLength = ref(parseInt(localStorage.getItem(KEYS.maxLen) || '4', 10));
const knownLetters = ref(localStorage.getItem(KEYS.letters) || 'aeiou');

watch(kidName, (val) => localStorage.setItem(KEYS.name, val));
watch(minWordLength, (val) => localStorage.setItem(KEYS.minLen, String(val)));
watch(maxWordLength, (val) => localStorage.setItem(KEYS.maxLen, String(val)));
watch(knownLetters, (val) => localStorage.setItem(KEYS.letters, val));

export const LETTER_PRESETS = {
    en: {
        vowels: 'aeiou',
        beginners: 'aeiourtnspl',
        intermediate: 'aeiourtnspdlfghkm',
    },
    nl: {
        vowels: 'aeiou',
        beginners: 'aeiourtnspl',
        intermediate: 'aeiourtnspdlfghkm',
    },
};

export function useKidSettings() {
    const setName = (name: string) => {
        kidName.value = name;
    };

    const setWordLengthRange = (min: number, max: number) => {
        minWordLength.value = Math.max(2, Math.min(min, 8));
        maxWordLength.value = Math.max(min, Math.min(max, 8));
    };

    const setKnownLetters = (letters: string) => {
        knownLetters.value = letters.toLowerCase();
    };

    const toggleLetter = (letter: string) => {
        const l = letter.toLowerCase();
        if (knownLetters.value.includes(l)) {
            knownLetters.value = knownLetters.value.replace(l, '');
        } else {
            knownLetters.value += l;
        }
    };

    const applyPreset = (preset: string) => {
        knownLetters.value = preset;
    };

    return {
        kidName,
        minWordLength,
        maxWordLength,
        knownLetters,
        setName,
        setWordLengthRange,
        setKnownLetters,
        toggleLetter,
        applyPreset,
    };
}
