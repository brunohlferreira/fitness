<script setup>
import { Inertia } from "@inertiajs/inertia";
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Label from "@/Components/Label.vue";
import Input from "@/Components/Input.vue";
import Button from "@/Components/Button.vue";

const props = defineProps({
    workout: Object,
});
</script>

<template>
    <Head title="Show Workout" />

    <AuthenticatedLayout>
        <h2
            v-if="workout.data.name.length"
            class="mt-1 mb-3 text-xl text-center"
        >
            {{ props.workout.data.name }}
        </h2>
        <ContentBox>
            <template #title>
                <ContentTitle>{{
                    new Date(`${workout.data.date}z`).toLocaleString("en-US", {
                        weekday: "long",
                        year: "numeric",
                        month: "long",
                        day: "numeric",
                    })
                }}</ContentTitle>
            </template>

            <template #actions>
                <Link
                    :href="`/workouts/${workout.data.id}/edit`"
                    class="block hover:text-blue-500"
                    ><FontAwesomeIcon icon="pencil"></FontAwesomeIcon
                ></Link>
            </template>

            <template #content>
                <div
                    class="
                        grid grid-flow-col
                        auto-cols-auto
                        text-center text-green-600
                    "
                >
                    <div>
                        <span v-if="workout.data.level === 1">Beginner</span>
                        <span v-else-if="workout.data.level === 2"
                            >Intermediate</span
                        >
                        <span v-else-if="workout.data.level === 3"
                            >Advanced</span
                        >
                        <small
                            class="
                                block
                                uppercase
                                text-xs text-gray-500
                                dark:text-gray-400
                            "
                            >Level</small
                        >
                    </div>
                    <div>
                        <span :title="workout.data.workout_type_description">{{
                            workout.data.workout_type_name
                        }}</span>
                        <small
                            class="
                                block
                                uppercase
                                text-xs text-gray-500
                                dark:text-gray-400
                            "
                            >Type</small
                        >
                    </div>
                    <div v-if="workout.data.time_cap > 0">
                        {{ workout.data.time_cap }}
                        min
                        <small
                            class="
                                block
                                uppercase
                                text-xs text-gray-500
                                dark:text-gray-400
                            "
                            >Time Cap</small
                        >
                    </div>
                    <div v-if="workout.data.score && workout.data.score.length">
                        {{ workout.data.score }}
                        <span v-if="workout.data.workout_type_name == 'AMRAP'">
                            rounds</span
                        >
                        <span
                            v-else-if="workout.data.workout_type_name == 'RFT'"
                        >
                            min</span
                        >
                        <small
                            class="
                                block
                                uppercase
                                text-xs text-gray-500
                                dark:text-gray-400
                            "
                            >Score</small
                        >
                    </div>
                </div>
                <div v-if="workout.data.description" class="mt-4">
                    <p>{{ workout.data.description }}</p>
                </div>
            </template>
        </ContentBox>

        <ContentBox class="mt-4" v-if="workout.data.exercises.length">
            <template #title>
                <h3>Exercises</h3>
            </template>

            <template #content>
                <ul class="-my-2">
                    <li
                        v-for="exercise in workout.data.exercises"
                        :key="exercise.id"
                        class="
                            py-2
                            border-t
                            first:border-0
                            dark:border-gray-700
                        "
                    >
                        <Link
                            :href="`/exercises/${exercise.id}`"
                            class="hover:text-blue-500"
                            >{{ exercise.name }}</Link
                        >
                        <ul class="text-xs text-gray-500 dark:text-gray-400">
                            <li
                                v-for="set in exercise.sets"
                                :key="set.id"
                                class="pt-1 first:pt-0"
                            >
                                <span v-if="set.repetitions"
                                    >{{ set.repetitions }}x
                                </span>
                                <span v-if="set.distance"
                                    >{{ set.distance }}m
                                </span>
                                <span v-if="parseInt(set.weight)"
                                    >{{ parseFloat(set.weight) }}kg
                                </span>
                                <span v-if="set.calories"
                                    >{{ set.calories }}cal
                                </span>
                                <span v-if="set.minutes"
                                    >{{ set.minutes }}min
                                </span>
                            </li>
                        </ul>
                    </li>
                </ul>
            </template>
        </ContentBox>

        <div class="flex justify-center mt-6">
            <Button
                @click="
                    Inertia.visit(`/workouts/create?workout=${workout.data.id}`)
                "
                class="block"
                >Do it again</Button
            >
        </div>
    </AuthenticatedLayout>
</template>