<script setup>
import { Inertia } from "@inertiajs/inertia";
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Table from "@/Components/Table.vue";
import Pagination from "@/Components/Pagination.vue";

defineProps({
    workoutTypes: Object,
});

const deleteEntry = function (id) {
    if (
        !window.confirm(
            "You are about to permanently delete this entry. Do you want to proceed?"
        )
    ) {
        return;
    }

    axios.delete("/workout-types/" + id).then((response) => {
        Inertia.reload();
    });
};
</script>

<template>
    <Head title="Workout Types" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>Workout Types</ContentTitle>
            </template>

            <template #actions>
                <Link :href="route('workoutTypes.create')" class="block"
                    ><FontAwesomeIcon icon="plus"></FontAwesomeIcon
                ></Link>
            </template>

            <template #content>
                <p v-if="!workoutTypes.data.length">No content was found.</p>

                <Table
                    v-else
                    :rows="workoutTypes.data"
                    :actions="true"
                    :editUrl="'/workout-types'"
                    :deleteFunction="deleteEntry"
                />
            </template>
        </ContentBox>

        <Pagination :links="workoutTypes.links" :meta="workoutTypes.meta" />
    </AuthenticatedLayout>
</template>