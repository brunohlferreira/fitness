<script setup>
import { ref, inject } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import { VueDraggableNext } from "vue-draggable-next";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Label from "@/Components/Label.vue";
import Input from "@/Components/Input.vue";
import Select from "@/Components/Select.vue";
import Textarea from "@/Components/Textarea.vue";
import Button from "@/Components/Button.vue";
import ModalExercisesAdd from "@/Components/ModalExercisesAdd.vue";
import ValidationErrors from "@/Components/ValidationErrors.vue";

const props = defineProps({
    workout: Object,
    workoutTypes: Object,
});

const darkMode = inject("darkMode");
const isOpen = ref(false);
const exercises = ref(
    props.workout !== null ? props.workout.data.exercises : []
);

const addExercise = function (id, name, note, sets) {
    if (note === undefined) note = "";
    if (sets === undefined) sets = [];
    exercises.value.push({ id: id, name: name, note: note, sets: sets });
};

const removeExercise = function (index) {
    exercises.value.splice(index, 1);
};

const addSet = function (index) {
    exercises.value[index].sets.push({
        repetitions: 0,
        weight: 0,
        distance: 0,
        calories: 0,
        minutes: 0,
    });
};

const form = useForm({
    name: props.workout !== null ? props.workout.data.name : "",
    description: props.workout !== null ? props.workout.data.description : "",
    date: new Date(),
    level: props.workout !== null ? props.workout.data.level : "1",
    time_cap: props.workout !== null ? props.workout.data.time_cap : "0",
    score: props.workout !== null ? props.workout.data.score : "",
    workout_type_id:
        props.workout !== null ? props.workout.data.workout_type_id : "1",
    workout_preset_id:
        props.workout !== null ? props.workout.data.workout_preset_id : null,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        exercises: exercises.value,
    })).post("/workouts");
};
</script>

<template>
    <Head title="Create Workout" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>Create Workout</ContentTitle>
            </template>

            <template #content>
                <ValidationErrors class="mb-4" />

                <form @submit.prevent="submit" autocomplete="off">
                    <div>
                        <div class="grid grid-cols-6 gap-4">
                            <div class="col-span-6 sm:col-span-4">
                                <Label for="name" value="Name" />
                                <Input
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                    autofocus
                                />
                            </div>
                            <div class="col-span-6 sm:col-span-2">
                                <Label for="date" value="Date" />
                                <Datepicker
                                    v-model="form.date"
                                    :dark="darkMode ?? undefined"
                                    class="mt-1"
                                    inputClassName="
                                        !border-gray-300
                                        !rounded-md
                                        !shadow-sm
                                        dark:!bg-zinc-900
                                        dark:!border-zinc-500
                                        !py-2
                                    "
                                ></Datepicker>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="grid grid-cols-6 gap-4">
                            <div class="col-span-6 sm:col-span-3">
                                <Label for="workout_type_id" value="Type" />
                                <Select
                                    id="workout_type_id"
                                    class="mt-1 block w-full"
                                    v-model="form.workout_type_id"
                                >
                                    <option
                                        v-for="(
                                            workoutType, index
                                        ) in workoutTypes.data"
                                        :key="index"
                                        :value="workoutType.id"
                                    >
                                        {{ workoutType.name }} ({{
                                            workoutType.description
                                        }})
                                    </option>
                                </Select>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <Label for="level" value="Level" />
                                <Select
                                    id="level"
                                    class="mt-1 block w-full"
                                    v-model="form.level"
                                >
                                    <option value="1">Beginner</option>
                                    <option value="2">Intermediate</option>
                                    <option value="3">Advanced</option>
                                </Select>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="grid grid-cols-6 gap-4">
                            <div class="col-span-6 sm:col-span-3">
                                <Label
                                    for="time_cap"
                                    value="Time cap (in minutes)"
                                />
                                <Input
                                    id="time_cap"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.time_cap"
                                />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <Label for="score" value="Score (ex: 14:25)" />
                                <Input
                                    id="score"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.score"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <Label for="description" value="Description" />
                        <Textarea
                            id="description"
                            class="mt-1 block w-full"
                            v-model="form.description"
                        />
                    </div>

                    <div class="mt-4">
                        <div class="flex justify-between mb-3">
                            <Label for="exercises" value="Exercises" />
                            <button
                                type="button"
                                @click="isOpen = true"
                                class="hover:text-blue-500"
                                title="Add exercise"
                            >
                                <FontAwesomeIcon icon="plus"></FontAwesomeIcon>
                            </button>
                        </div>

                        <VueDraggableNext
                            class="dragArea list-group"
                            :list="exercises"
                            handle=".handle"
                        >
                            <div
                                v-for="(exercise, exerciseIndex) in exercises"
                                :key="exerciseIndex"
                                class="
                                    mb-5
                                    font-medium
                                    text-sm text-gray-700
                                    dark:text-neutral-200
                                "
                            >
                                <div class="flex justify-between">
                                    <h6 class="cursor-pointer handle">
                                        <span>{{ exerciseIndex + 1 }}.</span>
                                        {{ exercise.name }}
                                    </h6>
                                    <div>
                                        <button
                                            type="button"
                                            class="mx-1 hover:text-blue-500"
                                            @click.prevent="
                                                addSet(exerciseIndex)
                                            "
                                            title="Add set"
                                        >
                                            <FontAwesomeIcon
                                                icon="plus"
                                            ></FontAwesomeIcon>
                                        </button>
                                        <button
                                            type="button"
                                            class="ml-1 hover:text-blue-500"
                                            @click.prevent="
                                                removeExercise(exerciseIndex)
                                            "
                                            title="Remove exercise"
                                        >
                                            <FontAwesomeIcon
                                                icon="trash-can"
                                            ></FontAwesomeIcon>
                                        </button>
                                    </div>
                                </div>
                                <Input
                                    id="note"
                                    type="text"
                                    v-model="exercises[exerciseIndex].note"
                                    placeholder="Note"
                                    class="my-2 block w-full p-1"
                                />
                                <table v-if="exercise.sets.length">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="pr-1">
                                                Set
                                            </th>
                                            <th scope="col" title="Repetitions">
                                                Rep
                                            </th>
                                            <th
                                                scope="col"
                                                title="Weight in kg"
                                            >
                                                Wt
                                            </th>
                                            <th
                                                scope="col"
                                                title="Distance in meters"
                                            >
                                                Dist
                                            </th>
                                            <th scope="col" title="Calories">
                                                Cal
                                            </th>
                                            <th
                                                scope="col"
                                                title="Time in minutes"
                                            >
                                                Min
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(
                                                set, setIndex
                                            ) in exercise.sets"
                                            :key="setIndex"
                                        >
                                            <th scope="row">
                                                {{ setIndex + 1 }}
                                            </th>
                                            <td>
                                                <Input
                                                    type="text"
                                                    v-model="
                                                        exercises[exerciseIndex]
                                                            .sets[setIndex]
                                                            .repetitions
                                                    "
                                                    class="
                                                        block
                                                        w-full
                                                        p-1
                                                        text-center
                                                    "
                                                />
                                            </td>
                                            <td>
                                                <Input
                                                    type="text"
                                                    v-model="
                                                        exercises[exerciseIndex]
                                                            .sets[setIndex]
                                                            .weight
                                                    "
                                                    class="
                                                        block
                                                        w-full
                                                        p-1
                                                        b-0
                                                        text-center
                                                    "
                                                />
                                            </td>
                                            <td>
                                                <Input
                                                    type="text"
                                                    v-model="
                                                        exercises[exerciseIndex]
                                                            .sets[setIndex]
                                                            .distance
                                                    "
                                                    class="
                                                        block
                                                        w-full
                                                        p-1
                                                        b-0
                                                        text-center
                                                    "
                                                />
                                            </td>
                                            <td>
                                                <Input
                                                    type="text"
                                                    v-model="
                                                        exercises[exerciseIndex]
                                                            .sets[setIndex]
                                                            .calories
                                                    "
                                                    class="
                                                        block
                                                        w-full
                                                        p-1
                                                        b-0
                                                        text-center
                                                    "
                                                />
                                            </td>
                                            <td>
                                                <Input
                                                    type="text"
                                                    v-model="
                                                        exercises[exerciseIndex]
                                                            .sets[setIndex]
                                                            .minutes
                                                    "
                                                    class="
                                                        block
                                                        w-full
                                                        p-1
                                                        b-0
                                                        text-center
                                                    "
                                                />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </VueDraggableNext>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <Button
                            type="submit"
                            class="ml-4"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Create
                        </Button>
                    </div>

                    <ModalExercisesAdd
                        :open="isOpen"
                        @close="isOpen = !isOpen"
                        @add-exercise="addExercise"
                    >
                    </ModalExercisesAdd>
                </form>
            </template>
        </ContentBox>
    </AuthenticatedLayout>
</template>