<script setup>
import AuthenticatedLayout from '@/Layouts/Authenticated.vue';
</script>

<template>
    <Head title="Backoffice Dashboard" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4">
            <h2 class="dark:text-neutral-200">Models</h2>

            <Link :href="route('backoffice.bodyParts')" class="dark:text-blue-500">Body Parts</Link>
        </div>
    </AuthenticatedLayout>
</template>