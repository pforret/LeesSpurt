<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const version = page.props.version as string;
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
        <footer class="flex items-center justify-center gap-4 p-4 text-sm text-sky-600 dark:text-sky-400">
            <span>© 2026 Peter Forret</span>
            <span>v{{ version }}</span>
            <a
                href="https://github.com/pforret"
                target="_blank"
                rel="noopener noreferrer"
                class="hover:text-sky-800 dark:hover:text-sky-200"
            >
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/>
                </svg>
            </a>
            <a
                href="https://twitter.com/pforret"
                target="_blank"
                rel="noopener noreferrer"
                class="hover:text-sky-800 dark:hover:text-sky-200"
            >
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                </svg>
            </a>
        </footer>
    </div>
</template>
