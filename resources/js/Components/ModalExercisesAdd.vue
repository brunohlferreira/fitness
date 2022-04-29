<script setup>
import { ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import debounce from "lodash/debounce";
import Label from "@/Components/Label.vue";
import Input from "@/Components/Input.vue";

const props = defineProps({
    open: {
        type: Boolean,
        required: true,
    },
});

const emit = defineEmits(["add-exercise", "close"]);

const search = ref("");
const exercises = ref([]);

const addExercise = function (event, id, name) {
    event.target.disabled = "disabled";
    event.target.childNodes[0].nodeValue = "Added";
    setInterval(() => {
        event.target.disabled = "";
        event.target.childNodes[0].nodeValue = "Add";
    }, 1000);
    emit("add-exercise", id, name, "");
};

watch(
    search,
    debounce(function (value) {
        axios.get(`/exercises?modalSearch=${value}`).then(function (response) {
            exercises.value = response.data;
        });
    }, 300)
);
</script>

<template>
    <div
        id="large-modal"
        v-show="open"
        class="
            overflow-y-auto overflow-x-hidden
            fixed
            top-0
            right-0
            left-0
            z-50
            w-full
            md:inset-0
            h-modal h-full
            bg-zinc-500/50
        "
    >
        <div class="relative p-4 w-full max-w-4xl h-full md:h-auto m-auto">
            <div class="relative bg-white rounded-lg shadow dark:bg-zinc-800">
                <div
                    class="
                        flex
                        justify-between
                        items-center
                        p-5
                        rounded-t
                        border-b
                        dark:border-gray-600
                    "
                >
                    <h3
                        class="
                            text-xl
                            font-medium
                            text-gray-900
                            dark:text-white
                        "
                    >
                        Add exercises
                    </h3>
                    <button
                        type="button"
                        @click="$emit('close')"
                        class="
                            text-gray-400
                            bg-transparent
                            hover:bg-gray-200 hover:text-gray-900
                            rounded-lg
                            text-sm
                            p-1.5
                            ml-auto
                            inline-flex
                            items-center
                            dark:hover:bg-gray-600 dark:hover:text-white
                        "
                    >
                        <svg
                            class="w-5 h-5"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-6">
                    <form autocomplete="off">
                        <div>
                            <Label for="search" value="Name" />
                            <Input
                                id="search"
                                v-model="search"
                                type="text"
                                class="mt-1 block w-full"
                            />
                        </div>
                        <div class="mt-2">
                            <ul>
                                <li
                                    v-for="(exercise, index) in exercises"
                                    :key="index"
                                    class="my-1"
                                >
                                    <div class="flex justify-between">
                                        <div>{{ exercise.name }}</div>
                                        <div>
                                            <button
                                                @click.prevent="
                                                    addExercise(
                                                        $event,
                                                        exercise.id,
                                                        exercise.name,
                                                        ''
                                                    )
                                                "
                                            >
                                                Add
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>