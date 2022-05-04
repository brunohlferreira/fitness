<script setup>
import { ref, watch } from "vue";
import debounce from "lodash/debounce";
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Label from "@/Components/Label.vue";
import Input from "@/Components/Input.vue";
import Table from "@/Components/Table.vue";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    exercises: Object,
    can: Object,
});

const isOpen = ref(false);
const collapsible = ref();
const search = ref("");
const exercises = ref(props.exercises);

watch(isOpen, (value) => {
    collapsible.value.style.height = value
        ? collapsible.value.scrollHeight + "px"
        : 0;
});

watch(
    search,
    debounce(function (value) {
        axios.get(`/exercises?search=${value}`).then(function (response) {
            exercises.value = response.data;
        });
    }, 300)
);
</script>

<template>
    <Head title="Exercises" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>Exercises</ContentTitle>
            </template>

            <template #actions>
                <div>
                    <button
                        @click="isOpen = !isOpen"
                        class="hover:text-blue-500"
                    >
                        <FontAwesomeIcon :icon="!isOpen ? 'filter' : 'filter-circle-xmark'"></FontAwesomeIcon>
                    </button>
                    <Link
                        v-if="can.create"
                        :href="route('exercises.create')"
                        class="ml-4 hover:text-blue-500"
                        ><FontAwesomeIcon icon="plus"></FontAwesomeIcon
                    ></Link>
                </div>
            </template>

            <template #subContent>
                <div
                    class="collapsible"
                    :class="{ open: isOpen }"
                    ref="collapsible"
                >
                    <div class="my-2">
                        <Input
                            id="search"
                            v-model="search"
                            type="text"
                            class="mt-1 block w-full"
                            placeholder="Filter by..."
                        />
                    </div>
                </div>
            </template>

            <template #content>
                <p v-if="!exercises.data.length">No content was found.</p>

                <Table
                    v-else
                    :rows="exercises.data"
                    :canUpdate="can.update"
                    :canDelete="can.delete"
                    viewUrl="/exercises/%d"
                    editUrl="/exercises/%d/edit"
                    deleteUrl="/exercises/%d"
                />
            </template>
        </ContentBox>

        <Pagination :links="exercises.links" :meta="exercises.meta" />
    </AuthenticatedLayout>
</template>

<style scoped>
.collapsible {
    overflow: hidden;
    transition: height 0.3s ease-out;
    height: 0;
}
.collapsible.open {
    height: auto;
}
</style>