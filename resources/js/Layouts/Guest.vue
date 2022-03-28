<script setup>
import { Link } from '@inertiajs/inertia-vue3';

const darkMode = localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
if (darkMode) {
    localStorage.theme = 'dark';
    document.documentElement.classList.add('dark');
}
</script>

<template>
    <div class="min-h-screen h-screen flex flex-col items-center justify-center bg-gray-100 dark:bg-zinc-900">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg dark:bg-zinc-800 dark:border-zinc-900 dark:text-neutral-200">
            <slot />
        </div>
    </div>
</template>
