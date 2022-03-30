<script setup>
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Table from "@/Components/Table.vue";

const models = [
    {
        name: "Body Parts",
        route: "backoffice.bodyParts.index",
    },
];
</script>

<template>
    <Head title="Backoffice Dashboard" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>Models</ContentTitle>
            </template>

            <template #content>
                <Table :rows="models" />
            </template>
        </ContentBox>
    </AuthenticatedLayout>
</template>