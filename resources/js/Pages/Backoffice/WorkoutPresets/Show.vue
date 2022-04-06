<script setup>
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Label from "@/Components/Label.vue";
import Input from "@/Components/Input.vue";

let props = defineProps({
    workoutPreset: Object,
});
</script>

<template>
    <Head title="Show Workout Preset" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>{{ workoutPreset.data.name }}</ContentTitle>
            </template>

            <template #content>
                <div>
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <span v-if="workoutPreset.data.level === 1" class="text-green-600">Beginner</span>
                            <span v-else-if="workoutPreset.data.level === 2" class="text-orange-500">Intermediate</span>
                            <span v-else-if="workoutPreset.data.level === 3" class="text-red-600">Advanced</span>
					        <small class="block">LEVEL</small>
                        </div>

                        <div v-if="workoutPreset.data.time_cap > 0" class="col-span-6 sm:col-span-3">
                            {{ workoutPreset.data.time_cap }} {{ workoutPreset.data.timeCap.indexOf(':') > 1 ? ':00' : '' }} min
					        <small class="block">TIME CAP</small>
                        </div>
                    </div>
                </div>
            </template>
        </ContentBox>
        <ContentBox class="mt-4">
            <template #title>
                <h3>Exercises</h3>
            </template>

            <template #actions>
                <Link :href="route('backoffice.workoutPresets.exercises.create')" class="block" title="Add exercises"
                    ><FontAwesomeIcon icon="plus"></FontAwesomeIcon
                ></Link>
                <Link :href="route('backoffice.workoutPresets.exercises.edit')" class="block" title="Manage exercises"
                    ><FontAwesomeIcon icon="checkList"></FontAwesomeIcon
                ></Link>
            </template>

            <template #content>
                <form @submit.prevent="submit">
                    <div class="mb-6">
                        <Label for="search" value="Search" />
                        <Input id="search" type="text" class="mt-1 block w-full" v-model="search" placeholder="Search..." />
                    </div>
                </form>
            </template>
        </ContentBox>
    </AuthenticatedLayout>
</template>