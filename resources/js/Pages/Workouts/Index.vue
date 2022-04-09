<script setup>
import { Inertia } from "@inertiajs/inertia";
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Table from "@/Components/Table.vue";
import Pagination from "@/Components/Pagination.vue";

defineProps({
    workouts: Object,
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
                    class="block"
                    ><FontAwesomeIcon icon="plus"></FontAwesomeIcon
                ></Link>
            </template>

            <template #content>
                <p v-if="!workouts.data.length">No content was found.</p>

                <Table
                    v-else
                    :actions="true"
                    :rows="workouts.data"
                    :viewUrl="'/workouts'"
                    :editUrl="'/workouts'"
                />
            </template>
        </ContentBox>

        <Pagination :links="workouts.links" :meta="workouts.meta" />
    </AuthenticatedLayout>
</template>