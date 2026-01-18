<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    progress: number;
    timeRemaining: number;
}>();

const barColor = computed(() => {
    if (props.progress > 80) return 'bg-red-500';
    if (props.progress > 60) return 'bg-orange-500';
    return 'bg-green-500';
});
</script>

<template>
    <div class="w-full space-y-2">
        <div class="flex justify-between text-lg font-bold text-sky-800 dark:text-sky-200">
            <span>{{ timeRemaining }}s</span>
        </div>
        <div class="h-4 w-full overflow-hidden rounded-full bg-sky-200 dark:bg-sky-800">
            <div
                class="h-full transition-all duration-1000 ease-linear"
                :class="barColor"
                :style="{ width: `${100 - progress}%` }"
            />
        </div>
    </div>
</template>
