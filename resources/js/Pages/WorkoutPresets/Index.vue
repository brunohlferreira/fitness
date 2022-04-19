<script setup>
import { Inertia } from "@inertiajs/inertia";
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Table from "@/Components/Table.vue";
import Pagination from "@/Components/Pagination.vue";

defineProps({
    workoutPresets: Object,
    can: Object,
});

const deleteEntry = function (id) {
    if (
        !window.confirm(
            "You are about to permanently delete this entry. Do you want to proceed?"
        )
    ) {
        return;
    }

    axios.delete(`/workout-presets/${id}`).then((response) => {
        Inertia.visit("/workout-presets");
    });
};
</script>

<template>
    <Head title="Workout Presets" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>Workout Presets</ContentTitle>
            </template>

            <template #actions v-if="can.create">
                <Link :href="route('workoutPresets.create')" class="block"
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
                    :viewUrl="'/workout-presets'"
                    :editUrl="'/workout-presets'"
                    :deleteFunction="deleteEntry"
                />
            </template>
        </ContentBox>

        <Pagination :links="workoutPresets.links" :meta="workoutPresets.meta" />
    </AuthenticatedLayout>
</template>