<script setup>
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Table from "@/Components/Table.vue";
import Pagination from "@/Components/Pagination.vue";

defineProps({
    roles: Object,
});
</script>

<template>
    <Head title="Roles" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>Roles</ContentTitle>
            </template>

            <template #content>
                <p v-if="!roles.data.length">No content was found.</p>

                <Table
                    v-else
                    :rows="roles.data"
                    :actions="true"
                    :deleteAction="false"
                    :editUrl="'/roles'"
                />
            </template>
        </ContentBox>

        <Pagination :links="roles.links" :meta="roles.meta" />
    </AuthenticatedLayout>
</template>