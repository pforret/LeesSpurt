import { ref, watch } from 'vue';

const KEYS = {
    name: 'letterly.kidName',
    minLen: 'letterly.minWordLength',
    maxLen: 'letterly.maxWordLength',
    letters: 'letterly.knownLetters',
    age: 'letterly.kidAge',
};

const kidName = ref(localStorage.getItem(KEYS.name) || '');
const minWordLength = ref(parseInt(localStorage.getItem(KEYS.minLen) || '2', 10));
const maxWordLength = ref(parseInt(localStorage.getItem(KEYS.maxLen) || '4', 10));
const knownLetters = ref(localStorage.getItem(KEYS.letters) || 'aeiou');
const kidAge = ref(parseInt(localStorage.getItem(KEYS.age) || '5', 10));

watch(kidName, (val) => localStorage.setItem(KEYS.name, val));
watch(minWordLength, (val) => localStorage.setItem(KEYS.minLen, String(val)));
watch(maxWordLength, (val) => localStorage.setItem(KEYS.maxLen, String(val)));
watch(knownLetters, (val) => localStorage.setItem(KEYS.letters, val));
watch(kidAge, (val) => localStorage.setItem(KEYS.age, String(val)));

export const LETTER_PRESETS = {
    en: {
        vowels: 'aeiou',
        beginners: 'aeiourtnspl',
        intermediate: 'aeiourtnspdlfghkm',
    },
    nl: {
        vowels: 'aeiou',
        beginners: 'abdeghiklmnoprstuvw',
        intermediate: 'abcdefghijklmnopqrstuvwxyz',
    },
    fr: {
        vowels: 'aeiouy',
        beginners: 'aeiouylrsntp',
        intermediate: 'aeiouylrsntpdmcbf',
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

    const setAge = (age: number) => {
        kidAge.value = Math.max(5, Math.min(age, 12));
    };

    return {
        kidName,
        minWordLength,
        maxWordLength,
        knownLetters,
        kidAge,
        setName,
        setWordLengthRange,
        setKnownLetters,
        toggleLetter,
        applyPreset,
        setAge,
    };
}
