import { ref, computed, watch } from 'vue';

export type GamePhase = 'countdown' | 'playing' | 'finished';

const STORAGE_KEYS = {
    duration: 'leesSpurt.duration',
    threshold: 'leesSpurt.threshold',
    history: 'leesSpurt.history',
};

export interface GameResult {
    date: string;
    score: number;
    threshold: number;
    duration: number;
    passed: boolean;
}

const duration = ref(parseInt(localStorage.getItem(STORAGE_KEYS.duration) || '30', 10));
const threshold = ref(parseInt(localStorage.getItem(STORAGE_KEYS.threshold) || '10', 10));

watch(duration, (val) => localStorage.setItem(STORAGE_KEYS.duration, String(val)));
watch(threshold, (val) => localStorage.setItem(STORAGE_KEYS.threshold, String(val)));

export function useGameState() {
    const phase = ref<GamePhase>('countdown');
    const score = ref(0);
    const timeRemaining = ref(0);
    const currentWord = ref('');
    const countdownValue = ref(3);

    let timerInterval: ReturnType<typeof setInterval> | null = null;

    const setDuration = (d: number) => {
        duration.value = d;
    };

    const setThreshold = (t: number) => {
        threshold.value = t;
    };

    const startCountdown = (onComplete: () => void) => {
        phase.value = 'countdown';
        countdownValue.value = 3;

        const interval = setInterval(() => {
            countdownValue.value--;
            if (countdownValue.value <= 0) {
                clearInterval(interval);
                onComplete();
            }
        }, 1000);
    };

    const startGame = (onTick: () => void, onFinish: () => void) => {
        phase.value = 'playing';
        score.value = 0;
        timeRemaining.value = duration.value;

        timerInterval = setInterval(() => {
            timeRemaining.value--;
            onTick();
            if (timeRemaining.value <= 0) {
                endGame();
                onFinish();
            }
        }, 1000);
    };

    const incrementScore = () => {
        score.value++;
    };

    const endGame = () => {
        if (timerInterval) {
            clearInterval(timerInterval);
            timerInterval = null;
        }
        phase.value = 'finished';
    };

    const reset = () => {
        phase.value = 'countdown';
        score.value = 0;
        timeRemaining.value = duration.value;
        currentWord.value = '';
        countdownValue.value = 3;
    };

    const saveResult = () => {
        const result: GameResult = {
            date: new Date().toISOString(),
            score: score.value,
            threshold: threshold.value,
            duration: duration.value,
            passed: score.value >= threshold.value,
        };

        const history = getHistory();
        history.unshift(result);
        const trimmed = history.slice(0, 10);
        localStorage.setItem(STORAGE_KEYS.history, JSON.stringify(trimmed));

        return result;
    };

    const getHistory = (): GameResult[] => {
        try {
            return JSON.parse(localStorage.getItem(STORAGE_KEYS.history) || '[]');
        } catch {
            return [];
        }
    };

    const passed = computed(() => score.value >= threshold.value);
    const progress = computed(() =>
        duration.value > 0 ? ((duration.value - timeRemaining.value) / duration.value) * 100 : 0
    );

    return {
        duration,
        threshold,
        phase,
        score,
        timeRemaining,
        currentWord,
        countdownValue,
        passed,
        progress,
        setDuration,
        setThreshold,
        startCountdown,
        startGame,
        incrementScore,
        endGame,
        reset,
        saveResult,
        getHistory,
    };
}
