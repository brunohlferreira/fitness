<script setup>
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Label from "@/Components/Label.vue";
import Input from "@/Components/Input.vue";

const props = defineProps({
    workoutPreset: Object,
});

let columns = 2;
if (props.workoutPreset.data.time_cap) columns++;
</script>

<template>
    <Head title="Show Workout Preset" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>{{ workoutPreset.data.name }}</ContentTitle>
            </template>

            <template #content>
                <div class="flex text-center">
                    <div :class="columns == 2 ? 'w-1/2' : 'w-1/3'">
                        <span
                            v-if="workoutPreset.data.level === 1"
                            class="text-green-600"
                            >Beginner</span
                        >
                        <span
                            v-else-if="workoutPreset.data.level === 2"
                            class="text-orange-500"
                            >Intermediate</span
                        >
                        <span
                            v-else-if="workoutPreset.data.level === 3"
                            class="text-red-600"
                            >Advanced</span
                        >
                        <small class="block uppercase text-xs text-gray-400">Level</small>
                    </div>

                    <div :class="columns == 2 ? 'w-1/2' : 'w-1/3'">
                        <span :title="workoutPreset.data.workout_type_description">{{ workoutPreset.data.workout_type_name }}</span>
                        <small class="block uppercase text-xs text-gray-400">Type</small>
                    </div>

                    <div
                        v-if="workoutPreset.data.time_cap > 0"
                        :class="columns == 2 ? 'w-1/2' : 'w-1/3'"
                    >
                        {{ workoutPreset.data.time_cap }}
                        {{
                            workoutPreset.data.timeCap.indexOf(":") > 1
                                ? ":00"
                                : ""
                        }}
                        min
                        <small class="block uppercase text-xs text-gray-400">Time Cap</small>
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
                <ul>
                    <li
                    v-for="exercise in workoutPreset.data.exercises"
                    :key="exercise.id"
                    class="pb-2 mb-2 border-b dark:border-gray-700"
                >
                        <span>{{ exercise.name }}</span>
                        <ul class="text-xs text-gray-400">
                            <li v-for="set in exercise.sets" :key="set.id">
                                <span v-if="set.repetitions">{{ set.repetitions }}x</span>
                                <span v-if="set.distance">{{ set.distance }}m</span>
                                <span v-if="parseInt(set.weight)">{{ set.weight }}kg</span>
                                <span v-if="set.calories">{{ set.calories }}cal</span>
                                <span v-if="set.minutes">{{ set.minutes }}min</span>
                            </li>
                        </ul>
                    </li>
                </ul>
            </template>
        </ContentBox>
    </AuthenticatedLayout>
</template>