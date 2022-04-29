<script setup>
import { Inertia } from "@inertiajs/inertia";
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Button from "@/Components/Button.vue";

defineProps({
    workouts: Object,
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>Wods done</ContentTitle>
            </template>

            <template #content>
                <span v-if="!workouts.lifetime.total"
                    >You didn't register any workouts yet.</span
                >
                <span v-else
                    >You have registered {{ workouts.lifetime.total }} workouts
                    so far. Keep going!</span
                >
            </template>
        </ContentBox>

        <div class="flex justify-center mt-6">
            <Button @click="Inertia.visit('/workouts/create')" class="block"
                >Start Workout</Button
            >
        </div>
    </AuthenticatedLayout>
</template>
