<script setup>
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Table from "@/Components/Table.vue";
import Pagination from "@/Components/Pagination.vue";

defineProps({
    bodyParts: Object,
    can: Object,
});
</script>

<template>
    <Head title="Body Parts" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>Body Parts</ContentTitle>
            </template>

            <template #actions v-if="can.create">
                <Link
                    :href="route('bodyParts.create')"
                    class="block hover:text-blue-500"
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
                    editUrl="/body-parts/%d/edit"
                    deleteUrl="/body-parts/%d"
                />
            </template>
        </ContentBox>

        <Pagination :links="bodyParts.links" :meta="bodyParts.meta" />
    </AuthenticatedLayout>
</template>