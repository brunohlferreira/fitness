<script setup>
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Label from "@/Components/Label.vue";
import Input from "@/Components/Input.vue";

const props = defineProps({
    exercise: Object,
});
</script>

<template>
    <Head title="Show Exercise" />

    <AuthenticatedLayout>
        <ContentBox :content="typeof exercise.data.description == 'string'">
            <template #title>
                <ContentTitle
                    >{{ exercise.data.name }} ({{
                        exercise.data.bilateral ? "Bilateral" : "Unilateral"
                    }})</ContentTitle
                >
            </template>

            <template #content>
                <p>{{ exercise.data.description }}</p>
            </template>
        </ContentBox>
        <div class="grid grid-cols-6 gap-6">
            <div class="col-span-3">
                <ContentBox class="mt-4">
                    <template #title>
                        <h3>Body parts</h3>
                    </template>

                    <template #content>
                        <ul>
                            <li
                                v-for="bodyPart in exercise.data.bodyParts"
                                :key="bodyPart.id"
                                class="pb-2 mb-2 border-b dark:border-gray-700"
                            >
                                <span>{{ bodyPart.name }}</span>
                                <span class="text-xs text-gray-400">{{
                                    bodyPart.impact
                                }}</span>
                            </li>
                        </ul>
                    </template>
                </ContentBox>
            </div>
            <div class="col-span-3">
                <ContentBox class="mt-4">
                    <template #title>
                        <h3>Equipments</h3>
                    </template>

                    <template #content>
                        <ul>
                            <li
                                v-for="equipment in exercise.data.equipments"
                                :key="equipment.id"
                                class="pb-2 mb-2 border-b dark:border-gray-700"
                            >
                                <span>{{ equipment.name }}</span>
                            </li>
                        </ul>
                    </template>
                </ContentBox>
            </div>
        </div>
    </AuthenticatedLayout>
</template>