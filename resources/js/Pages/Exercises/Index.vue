<script setup>
import { Inertia } from "@inertiajs/inertia";
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Table from "@/Components/Table.vue";
import Pagination from "@/Components/Pagination.vue";

defineProps({
    exercises: Object,
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

    axios.delete(`/exercises/${id}`).then((response) => {
        Inertia.visit("/exercises");
    });
};
</script>

<template>
    <Head title="Exercises" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>Exercises</ContentTitle>
            </template>

            <template #actions v-if="can.create">
                <Link
                    :href="route('exercises.create')"
                    class="block hover:text-blue-500"
                    ><FontAwesomeIcon icon="plus"></FontAwesomeIcon
                ></Link>
            </template>

            <template #content>
                <p v-if="!exercises.data.length">No content was found.</p>

                <Table
                    v-else
                    :rows="exercises.data"
                    :canUpdate="can.update"
                    :canDelete="can.delete"
                    viewUrl="/exercises/%d"
                    editUrl="/exercises/%d/edit"
                    :deleteFunction="deleteEntry"
                />
            </template>
        </ContentBox>

        <Pagination :links="exercises.links" :meta="exercises.meta" />
    </AuthenticatedLayout>
</template>