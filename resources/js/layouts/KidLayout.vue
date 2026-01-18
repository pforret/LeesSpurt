<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-svh flex-col bg-gradient-to-b from-sky-100 to-sky-200 dark:from-sky-900 dark:to-sky-950"
    >
        <header class="p-4">
            <Link href="/" class="text-2xl font-bold text-sky-700 dark:text-sky-300">
                LeesSpurt
            </Link>
        </header>
        <main class="flex flex-1 flex-col items-center justify-center p-4">
            <slot />
        </main>
    </div>
</template>
