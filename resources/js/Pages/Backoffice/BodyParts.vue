<script setup>
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";

defineProps({
    bodyParts: Object,
});
</script>

<template>
    <Head title="Backoffice Body Parts" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4">
            <h2 class="dark:text-neutral-200">Body Parts</h2>

            <p v-if="!bodyParts.data.length">No content was found.</p>

            <ul v-for="bodyPart in bodyParts.data" :key="bodyPart.id">
                <li>bodyPart.name</li>
            </ul>
        </div>
    </AuthenticatedLayout>
</template>