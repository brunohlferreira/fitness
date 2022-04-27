<script setup>
import { Inertia } from "@inertiajs/inertia";
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Table from "@/Components/Table.vue";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    workouts: Object,
});

const deleteEntry = function (id) {
    if (
        !window.confirm(
            "You are about to permanently delete this entry. Do you want to proceed?"
        )
    ) {
        return;
    }

    axios.delete(`/workouts/${id}`).then((response) => {
        Inertia.visit("/workouts");
    });
};

const rows = [];
props.workouts.data.forEach((workout) => {
    rows.push({
        id: workout.id,
        name: new Date(workout.date).toLocaleString("en-US", {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric",
        }),
        info: workout.name,
    });
});
</script>

<template>
    <Head title="Workouts" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>Workouts</ContentTitle>
            </template>

            <template #actions>
                <Link
                    :href="route('workouts.create')"
                    class="block hover:text-blue-500"
                    ><FontAwesomeIcon icon="plus"></FontAwesomeIcon
                ></Link>
            </template>

            <template #content>
                <p v-if="!workouts.data.length">No content was found.</p>

                <Table
                    v-else
                    :rows="rows"
                    :canUpdate="true"
                    :canDelete="true"
                    viewUrl="/workouts/%d"
                    editUrl="/workouts/%d/edit"
                    :deleteFunction="deleteEntry"
                />
            </template>
        </ContentBox>

        <Pagination :links="workouts.links" :meta="workouts.meta" />
    </AuthenticatedLayout>
</template>