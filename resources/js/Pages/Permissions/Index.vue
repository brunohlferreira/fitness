<script setup>
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Table from "@/Components/Table.vue";
import Pagination from "@/Components/Pagination.vue";

defineProps({
    permissions: Object,
});
</script>

<template>
    <Head title="Permissions" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>Permissions</ContentTitle>
            </template>

            <template #content>
                <p v-if="!permissions.data.length">No content was found.</p>

                <Table
                    v-else
                    :rows="permissions.data"
                    :actions="true"
                    :deleteAction="false"
                    :editUrl="'/permissions'"
                />
            </template>
        </ContentBox>

        <Pagination :links="permissions.links" :meta="permissions.meta" />
    </AuthenticatedLayout>
</template>