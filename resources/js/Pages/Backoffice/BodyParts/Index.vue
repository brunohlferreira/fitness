<script setup>
import { Inertia } from "@inertiajs/inertia";
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Table from "@/Components/Table.vue";
import Pagination from "@/Components/Pagination.vue";

defineProps({
    bodyParts: Object,
});

const deleteRecord = function (id) {
    if (!window.confirm("You are about to permanently delete this entry. Do you want to proceed?")) {
        return;
    }

    axios.delete("/backoffice/body-parts/" + id).then((response) => {
        Inertia.reload();
    });
};
</script>

<template>
    <Head title="Backoffice Body Parts" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle
                    >Body Parts</ContentTitle
                >
            </template>

            <template #actions>
                <Link :href="route('backoffice.bodyParts.create')" class="block"
                    ><FontAwesomeIcon icon="plus"></FontAwesomeIcon
                ></Link>
            </template>

            <template #content>
                <p v-if="!bodyParts.data.length">No content was found.</p>

                <Table v-else :rows="bodyParts.data" :actions="true" />
            </template>
        </ContentBox>

        <Pagination :links="bodyParts.links" :meta="bodyParts.meta" />
    </AuthenticatedLayout>
</template>