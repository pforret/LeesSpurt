<script setup lang="ts">
import { useLanguage } from '@/composables/useLanguage';

defineProps<{
    value: number;
}>();

const { t } = useLanguage();
</script>

<template>
    <div
        class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-sky-500/90 dark:bg-sky-800/90"
    >
        <p class="mb-8 text-3xl font-medium text-white">
            {{ t({ en: 'Good luck!', nl: 'Veel succes!' }) }}
        </p>
        <span
            class="animate-pulse text-[200px] font-bold text-white drop-shadow-lg"
            :key="value"
        >
            {{ value }}
        </span>
    </div>
</template>
