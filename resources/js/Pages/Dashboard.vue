<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-2">
            <h2 class="px-2 dark:text-neutral-200">Wods done</h2>

            <div class="mt-2 bg-white overflow-hidden shadow-sm sm:rounded-lg dark:bg-zinc-800">
                <div class="p-4 bg-white border-b border-gray-200 dark:bg-zinc-800 dark:text-neutral-200 dark:border-0">
                    You didn't register any workouts yet.
                </div>
            </div>

            <Link href="/">Create a new wod</Link>
        </div>
    </BreezeAuthenticatedLayout>
</template>
