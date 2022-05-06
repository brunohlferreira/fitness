<script setup>
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Table from "@/Components/Table.vue";
import Pagination from "@/Components/Pagination.vue";

defineProps({
    workoutPresets: Object,
    can: Object,
});
</script>

<template>
    <Head title="Workout Presets" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>Workout Presets</ContentTitle>
            </template>

            <template #actions v-if="can.create">
                <Link
                    :href="route('workoutPresets.create')"
                    class="block hover:text-blue-500"
                    title="Create workout"
                    ><FontAwesomeIcon icon="plus"></FontAwesomeIcon
                ></Link>
            </template>

            <template #content>
                <p v-if="!workoutPresets.data.length">No content was found.</p>

                <Table
                    v-else
                    :rows="workoutPresets.data"
                    :canUpdate="can.update"
                    :canDelete="can.delete"
                    viewUrl="/workout-presets/%d"
                    editUrl="/workout-presets/%d/edit"
                    deleteUrl="/workout-presets/%d"
                />
            </template>
        </ContentBox>

        <Pagination :links="workoutPresets.links" :meta="workoutPresets.meta" />
    </AuthenticatedLayout>
</template>