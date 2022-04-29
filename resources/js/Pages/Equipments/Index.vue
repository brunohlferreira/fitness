<script setup>
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Table from "@/Components/Table.vue";
import Pagination from "@/Components/Pagination.vue";

defineProps({
    equipments: Object,
    can: Object,
});
</script>

<template>
    <Head title="Equipments" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>Equipments</ContentTitle>
            </template>

            <template #actions v-if="can.create">
                <Link
                    :href="route('equipments.create')"
                    class="block hover:text-blue-500"
                    ><FontAwesomeIcon icon="plus"></FontAwesomeIcon
                ></Link>
            </template>

            <template #content>
                <p v-if="!equipments.data.length">No content was found.</p>

                <Table
                    v-else
                    :rows="equipments.data"
                    :canUpdate="can.update"
                    :canDelete="can.delete"
                    editUrl="/equipments/%d/edit"
                    deleteUrl="/equipments/%d"
                />
            </template>
        </ContentBox>

        <Pagination :links="equipments.links" :meta="equipments.meta" />
    </AuthenticatedLayout>
</template>