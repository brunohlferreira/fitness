<script setup>
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Button from "@/Components/Button.vue";
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>Wods done</ContentTitle>
            </template>

            <template #content>
                You didn't register any workouts yet.
            </template>
        </ContentBox>

        <div class="flex justify-center mt-6">
            <Button href="/" class="block">Register Workout</Button>
        </div>
    </AuthenticatedLayout>
</template>
