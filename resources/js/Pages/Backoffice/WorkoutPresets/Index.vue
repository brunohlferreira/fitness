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

const deleteEntry = function (id) {
    if (
        !window.confirm(
            "You are about to permanently delete this entry. Do you want to proceed?"
        )
    ) {
        return;
    }

    axios.delete("/backoffice/workout-presets/" + id).then((response) => {
        Inertia.reload();
    });
};
</script>

<template>
    <Head title="Backoffice Workout Presets" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>Workout Presets</ContentTitle>
            </template>

            <template #actions>
                <Link
                    :href="route('backoffice.workoutPresets.create')"
                    class="block"
                    ><FontAwesomeIcon icon="plus"></FontAwesomeIcon
                ></Link>
            </template>

            <template #content>
                <p v-if="!workoutPresets.data.length">No content was found.</p>

                <Table
                    v-else
                    :rows="workoutPresets.data"
                    :actions="true"
                    :editUrl="'/backoffice/workout-presets'"
                    :deleteFunction="deleteEntry"
                />
            </template>
        </ContentBox>

        <Pagination :links="workoutPresets.links" :meta="workoutPresets.meta" />
    </AuthenticatedLayout>
</template>