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

const repeatWod = function (id) {
    axios.post(`/workouts/${id}/repeat`).then((response) => {
        Inertia.visit(`/workouts/${response.data}`);
    });
};

let name = props.workout.data.name.length
    ? " - " + props.workout.data.name
    : "";
let date = new Date(props.workout.data.date);
name =
    date.toISOString().split("T")[0] +
    " (" +
    date.toLocaleString("en-us", { weekday: "long" }) +
    ")" +
    name;

let columns = 2;
if (props.workout.data.time_cap) columns++;
</script>

<template>
    <Head title="Show Workout" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>{{ name }}</ContentTitle>
            </template>

            <template #actions>
                <Link :href="`/workouts/${workout.data.id}/edit`" class="block"
                    ><FontAwesomeIcon icon="pencil"></FontAwesomeIcon
                ></Link>
            </template>

            <template #content>
                <div class="flex text-center">
                    <div :class="columns == 2 ? 'w-1/2' : 'w-1/3'">
                        <span
                            v-if="workout.data.level === 1"
                            class="text-green-600"
                            >Beginner</span
                        >
                        <span
                            v-else-if="workout.data.level === 2"
                            class="text-orange-500"
                            >Intermediate</span
                        >
                        <span
                            v-else-if="workout.data.level === 3"
                            class="text-red-600"
                            >Advanced</span
                        >
                        <small class="block uppercase text-xs text-gray-400"
                            >Level</small
                        >
                    </div>

                    <div :class="columns == 2 ? 'w-1/2' : 'w-1/3'">
                        <span :title="workout.data.workout_type_description">{{
                            workout.data.workout_type_name
                        }}</span>
                        <small class="block uppercase text-xs text-gray-400"
                            >Type</small
                        >
                    </div>

                    <div
                        v-if="workout.data.time_cap > 0"
                        :class="columns == 2 ? 'w-1/2' : 'w-1/3'"
                    >
                        {{ workout.data.time_cap }}
                        min
                        <small class="block uppercase text-xs text-gray-400"
                            >Time Cap</small
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
                <ul>
                    <li
                        v-for="exercise in workout.data.exercises"
                        :key="exercise.id"
                        class="pb-2 mb-2 border-b dark:border-gray-700"
                    >
                        <Link :href="`/exercises/${exercise.id}`">{{
                            exercise.name
                        }}</Link>
                        <ul class="text-xs text-gray-400">
                            <li v-for="set in exercise.sets" :key="set.id">
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
            <Button @click="repeatWod(workout.data.id)" class="block"
                >Do it again</Button
            >
        </div>
    </AuthenticatedLayout>
</template>