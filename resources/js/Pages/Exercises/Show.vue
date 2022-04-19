<script setup>
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Label from "@/Components/Label.vue";
import Input from "@/Components/Input.vue";

const props = defineProps({
    exercise: Object,
    attempts: Object,
    can: Object,
});
</script>

<template>
    <Head title="Show Exercise" />

    <AuthenticatedLayout>
        <ContentBox
            :content="
                typeof exercise.data.description == 'string' &&
                exercise.data.description.length > 0
            "
        >
            <template #title>
                <ContentTitle
                    >{{ exercise.data.name }} ({{
                        exercise.data.bilateral ? "Bilateral" : "Unilateral"
                    }})</ContentTitle
                >
            </template>

            <template #actions v-if="can.update">
                <Link
                    :href="`/exercises/${exercise.data.id}/edit`"
                    class="block"
                    ><FontAwesomeIcon icon="pencil"></FontAwesomeIcon
                ></Link>
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

        <ContentBox class="mt-4" v-if="attempts.data.length">
            <template #title>
                <h3>Last attempts</h3>
            </template>

            <template #content>
                <ul>
                    <li
                        v-for="attempt in attempts.data"
                        :key="attempt.id"
                        class="
                            flex
                            justify-between
                            pb-2
                            mb-2
                            border-b
                            dark:border-gray-700
                        "
                    >
                        <div>
                            <Link :href="'/workouts/' + attempt.id">
                                {{
                                    new Date(attempt.date).toLocaleString(
                                        "en-US",
                                        {
                                            weekday: "long",
                                            year: "numeric",
                                            month: "long",
                                            day: "numeric",
                                        }
                                    )
                                }}
                            </Link>
                        </div>
                        <div>{{ attempt.score }}</div>
                    </li>
                </ul>
            </template>
        </ContentBox>
    </AuthenticatedLayout>
</template>