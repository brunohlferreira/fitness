<script setup>
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Table from "@/Components/Table.vue";
import Pagination from "@/Components/Pagination.vue";

defineProps({
    exercises: Object,
});
</script>

<template>
    <Head title="Exercises" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>Exercises</ContentTitle>
            </template>

            <template #content>
                <p v-if="!exercises.data.length">No content was found.</p>

                <Table
                    v-else
                    :rows="exercises.data"
                />
            </template>
        </ContentBox>

        <Pagination :links="exercises.links" :meta="exercises.meta" />
    </AuthenticatedLayout>
</template>