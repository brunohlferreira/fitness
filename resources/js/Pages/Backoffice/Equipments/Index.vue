<script setup>
import { Inertia } from "@inertiajs/inertia";
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Table from "@/Components/Table.vue";
import Pagination from "@/Components/Pagination.vue";

defineProps({
    equipments: Object,
});

const deleteRecord = function (id) {
    if (
        !window.confirm(
            "You are about to permanently delete this entry. Do you want to proceed?"
        )
    ) {
        return;
    }

    axios.delete("/backoffice/equipments/" + id).then((response) => {
        Inertia.reload();
    });
};
</script>

<template>
    <Head title="Backoffice Equipments" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>Equipments</ContentTitle>
            </template>

            <template #actions>
                <Link
                    :href="route('backoffice.equipments.create')"
                    class="block"
                    ><FontAwesomeIcon icon="plus"></FontAwesomeIcon
                ></Link>
            </template>

            <template #content>
                <p v-if="!equipments.data.length">No content was found.</p>

                <Table
                    v-else
                    :rows="equipments.data"
                    :actions="true"
                    :editUrl="'/backoffice/equipments'"
                    :deleteFunction="deleteRecord"
                />
            </template>
        </ContentBox>

        <Pagination :links="equipments.links" :meta="equipments.meta" />
    </AuthenticatedLayout>
</template>