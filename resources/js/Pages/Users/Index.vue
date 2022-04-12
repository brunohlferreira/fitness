<script setup>
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Table from "@/Components/Table.vue";
import Pagination from "@/Components/Pagination.vue";

defineProps({
    users: Object,
});
</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>Users</ContentTitle>
            </template>

            <template #content>
                <p v-if="!users.data.length">No content was found.</p>

                <Table
                    v-else
                    :rows="users.data"
                    :actions="true"
                    :deleteAction="false"
                    :editUrl="'/users'"
                />
            </template>
        </ContentBox>

        <Pagination :links="users.links" :meta="users.meta" />
    </AuthenticatedLayout>
</template>