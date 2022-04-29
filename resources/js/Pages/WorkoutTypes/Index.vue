<script setup>
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Table from "@/Components/Table.vue";
import Pagination from "@/Components/Pagination.vue";

defineProps({
    workoutTypes: Object,
    can: Object,
});
</script>

<template>
    <Head title="Workout Types" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>Workout Types</ContentTitle>
            </template>

            <template #actions v-if="can.create">
                <Link
                    :href="route('workoutTypes.create')"
                    class="block hover:text-blue-500"
                    ><FontAwesomeIcon icon="plus"></FontAwesomeIcon
                ></Link>
            </template>

            <template #content>
                <p v-if="!workoutTypes.data.length">No content was found.</p>

                <Table
                    v-else
                    :rows="workoutTypes.data"
                    :canUpdate="can.update"
                    :canDelete="can.delete"
                    editUrl="/workout-types/%d/edit"
                    deleteUrl="/workout-types/%d"
                />
            </template>
        </ContentBox>

        <Pagination :links="workoutTypes.links" :meta="workoutTypes.meta" />
    </AuthenticatedLayout>
</template>