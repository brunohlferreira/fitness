<script setup>
import { Inertia } from "@inertiajs/inertia";
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Table from "@/Components/Table.vue";
import Pagination from "@/Components/Pagination.vue";

defineProps({
    workoutPresets: Object,
});
</script>

<template>
    <Head title="Workout Presets" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>Workout Presets</ContentTitle>
            </template>

            <template #content>
                <p v-if="!workoutPresets.data.length">No content was found.</p>

                <Table
                    v-else
                    :rows="workoutPresets.data"
                    :viewUrl="'/workout-presets'"
                />
            </template>
        </ContentBox>

        <Pagination :links="workoutPresets.links" :meta="workoutPresets.meta" />
    </AuthenticatedLayout>
</template>