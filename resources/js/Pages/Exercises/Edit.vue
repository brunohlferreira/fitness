<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Label from "@/Components/Label.vue";
import Input from "@/Components/Input.vue";
import Select from "@/Components/Select.vue";
import Textarea from "@/Components/Textarea.vue";
import CheckboxButton from "@/Components/CheckboxButton.vue";
import Button from "@/Components/Button.vue";
import ValidationErrors from "@/Components/ValidationErrors.vue";

const props = defineProps({
    exercise: Object,
    bodyParts: Object,
    equipments: Object,
});

const bodyParts = ref([]);
const equipments = ref(props.equipments.data);

props.bodyParts.data.forEach((bodyPart) => {
    if (!bodyPart.impact) return;
    bodyParts.value.push({ id: bodyPart.id, impact: bodyPart.impact });
});

const addBodyPart = function () {
    bodyParts.value.push({ id: 0, impact: 2 });
};

const removeBodyPart = function () {
    bodyParts.value.pop();
};

const toggleEquipment = function (index) {
    equipments.value[index].selected = equipments.value[index].selected ? 0 : 1;
};

const form = useForm({
    name: props.exercise.data.name,
    bilateral: props.exercise.data.bilateral,
    description: props.exercise.data.description,
});

let submit = () => {
    form.transform((data) => ({
        ...data,
        bodyParts: bodyParts.value.filter((bodyPart) => {return bodyPart.id > 0;}),
        equipments: equipments.value.filter((equipment) => {return equipment.selected == 1;}),
    })).put(`/exercises/${props.exercise.data.id}`);
};
</script>

<template>
    <Head title="Edit Exercise" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>Edit Exercise</ContentTitle>
            </template>

            <template #content>
                <ValidationErrors class="mb-4" />

                <form @submit.prevent="submit" autocomplete="off">
                    <div class="mb-6">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
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

                            <div class="col-span-6 sm:col-span-3">
                                <Label for="bilateral" value="Type" />
                                <Select
                                    id="bilateral"
                                    class="mt-1 block w-full"
                                    v-model="form.bilateral"
                                >
                                    <option value="1">Bilateral</option>
                                    <option value="2">Unilateral</option>
                                </Select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <Label for="description" value="Description" />
                        <Textarea
                            id="description"
                            class="mt-1 block w-full"
                            v-model="form.description"
                        />
                    </div>

                    <div class="mb-6">
                        <div class="flex justify-between">
                            <Label value="Body parts and impact" />
                            <div>
                                <button type="button" @click="addBodyPart">
                                    <FontAwesomeIcon icon="plus"></FontAwesomeIcon>
                                </button>
                                <button type="button" @click="removeBodyPart" class="ml-2">
                                    <FontAwesomeIcon icon="minus"></FontAwesomeIcon>
                                </button>
                            </div>
                        </div>

                        <div
                            v-for="(
                                bodyPartExercise, bodyPartExerciseIndex
                            ) in bodyParts"
                            :key="bodyPartExerciseIndex"
                            class="
                                font-medium
                                text-sm text-gray-700
                                dark:text-neutral-200
                            "
                        >
                            <div class="grid grid-cols-6 gap-3 mb-2">
                                <div class="col-span-3">
                                    <Select
                                        id="bodyParts[]"
                                        class="mt-1 block w-full"
                                        v-model="
                                            bodyParts[bodyPartExerciseIndex].id
                                        "
                                    >
                                        <option value="0">
                                            Select an option
                                        </option>
                                        <option
                                            v-for="(
                                                bodyPart, bodyPartIndex
                                            ) in props.bodyParts.data"
                                            :key="bodyPartIndex"
                                            :value="bodyPart.id"
                                        >
                                            {{ bodyPart.name }}
                                        </option>
                                    </Select>
                                </div>

                                <div class="col-span-3">
                                    <Select
                                        id="bodyImpacts[]"
                                        class="mt-1 block w-full"
                                        v-model="
                                            bodyParts[bodyPartExerciseIndex]
                                                .impact
                                        "
                                    >
                                        <option value="1">Major</option>
                                        <option value="2">Minor</option>
                                    </Select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <Label value="Equipments" />
                        <CheckboxButton
                            v-for="(equipment, equipmentIndex) in equipments"
                            :key="equipmentIndex"
                            :selected="equipment.selected == 1"
                            @click.prevent="toggleEquipment(equipmentIndex)"
                        >
                            {{ equipment.name }}
                        </CheckboxButton>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <Button
                            type="submit"
                            class="ml-4"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Edit
                        </Button>
                    </div>
                </form>
            </template>
        </ContentBox>
    </AuthenticatedLayout>
</template>