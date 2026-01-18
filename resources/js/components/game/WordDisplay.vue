<script setup lang="ts">
defineProps<{
    word: string;
}>();
</script>

<template>
    <div
        class="flex min-h-[200px] items-center justify-center rounded-3xl bg-white p-8 shadow-lg dark:bg-sky-900"
    >
        <span
            class="select-none font-game text-6xl font-bold tracking-widest text-sky-800 sm:text-8xl dark:text-sky-100"
        >
            {{ word }}
        </span>
    </div>
</template>
