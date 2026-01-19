<script setup lang="ts">
defineProps<{
    word: string;
}>();
</script>

<template>
    <div class="card-playful flex min-h-[200px] items-center justify-center p-8">
        <span
            class="select-none font-game text-6xl font-bold tracking-widest sm:text-8xl"
        >
            <span class="bg-gradient-to-r from-sky-600 via-blue-600 to-cyan-600 bg-clip-text text-transparent dark:from-sky-400 dark:via-blue-400 dark:to-cyan-400">
                {{ word }}
            </span>
        </span>
    </div>
</template>
