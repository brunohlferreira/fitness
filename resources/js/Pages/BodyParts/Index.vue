<script setup>
import { Inertia } from "@inertiajs/inertia";
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Table from "@/Components/Table.vue";
import Pagination from "@/Components/Pagination.vue";

defineProps({
    bodyParts: Object,
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

    axios.delete(`/body-parts/${id}`).then((response) => {
        Inertia.visit("/body-parts");
    });
};
</script>

<template>
    <Head title="Body Parts" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>Body Parts</ContentTitle>
            </template>

            <template #actions v-if="can.create">
                <Link :href="route('bodyParts.create')" class="block"
                    ><FontAwesomeIcon icon="plus"></FontAwesomeIcon
                ></Link>
            </template>

            <template #content>
                <p v-if="!bodyParts.data.length">No content was found.</p>

                <Table
                    v-else
                    :rows="bodyParts.data"
                    :canUpdate="can.update"
                    :canDelete="can.delete"
                    :editUrl="'/body-parts'"
                    :deleteFunction="deleteEntry"
                />
            </template>
        </ContentBox>

        <Pagination :links="bodyParts.links" :meta="bodyParts.meta" />
    </AuthenticatedLayout>
</template>