<script setup>
import { Inertia } from "@inertiajs/inertia";
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Label from "@/Components/Label.vue";
import Input from "@/Components/Input.vue";
import Button from "@/Components/Button.vue";

const props = defineProps({
    workoutPreset: Object,
    attempts: Object,
    can: Object,
});
</script>

<template>
    <Head title="Show Workout Preset" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>{{ workoutPreset.data.name }}</ContentTitle>
            </template>

            <template #actions v-if="can.update">
                <Link
                    :href="`/workout-presets/${workoutPreset.data.id}/edit`"
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
                        <span v-if="workoutPreset.data.level === 1"
                            >Beginner</span
                        >
                        <span v-else-if="workoutPreset.data.level === 2"
                            >Intermediate</span
                        >
                        <span v-else-if="workoutPreset.data.level === 3"
                            >Advanced</span
                        >
                        <small class="block uppercase text-xs text-gray-400"
                            >Level</small
                        >
                    </div>
                    <div>
                        <span
                            :title="workoutPreset.data.workout_type_description"
                            >{{ workoutPreset.data.workout_type_name }}</span
                        >
                        <small class="block uppercase text-xs text-gray-400"
                            >Type</small
                        >
                    </div>
                    <div v-if="workoutPreset.data.time_cap > 0">
                        {{ workoutPreset.data.time_cap }}
                        min
                        <small class="block uppercase text-xs text-gray-400"
                            >Time Cap</small
                        >
                    </div>
                </div>
                <div v-if="workoutPreset.data.description" class="mt-4">
                    <p>{{ workoutPreset.data.description }}</p>
                </div>
            </template>
        </ContentBox>

        <ContentBox class="mt-4">
            <template #title>
                <h3>Exercises</h3>
            </template>

            <template #content>
                <ul class="-my-2">
                    <li
                        v-for="exercise in workoutPreset.data.exercises"
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
                                    >{{ set.repetitions }}x</span
                                >
                                <span v-if="set.distance"
                                    >{{ set.distance }}m</span
                                >
                                <span v-if="parseInt(set.weight)"
                                    >{{ set.weight }}kg</span
                                >
                                <span v-if="set.calories"
                                    >{{ set.calories }}cal</span
                                >
                                <span v-if="set.minutes"
                                    >{{ set.minutes }}min</span
                                >
                            </li>
                        </ul>
                    </li>
                </ul>
            </template>
        </ContentBox>

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
                            <Link
                                :href="`/workouts/${attempt.id}`"
                                class="hover:text-blue-500"
                            >
                                {{
                                    new Date(`${attempt.date}z`).toLocaleString(
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
                        <div>
                            {{ attempt.score }}
                            <span
                                v-if="
                                    workoutPreset.data.workout_type_name ==
                                    'AMRAP'
                                "
                            >
                                rounds</span
                            >
                            <span
                                v-else-if="
                                    workoutPreset.data.workout_type_name ==
                                    'RFT'
                                "
                            >
                                min</span
                            >
                        </div>
                    </li>
                </ul>
            </template>
        </ContentBox>

        <div class="flex justify-center mt-6">
            <Button
                @click="
                    Inertia.visit(
                        `/workouts/create?workoutPreset=${workoutPreset.data.id}`
                    )
                "
                class="block"
            >
                {{ attempts.data.length ? "Do it again" : "Do it" }}
            </Button>
        </div>
    </AuthenticatedLayout>
</template>