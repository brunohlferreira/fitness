<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Label from "@/Components/Label.vue";
import Input from "@/Components/Input.vue";
import Textarea from "@/Components/Textarea.vue";
import Button from "@/Components/Button.vue";
import ValidationErrors from "@/Components/ValidationErrors.vue";

let form = useForm({
    name: "",
    description: "",
});

let submit = () => {
    form.post("/workout-types");
};
</script>

<template>
    <Head title="Create Workout Types" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>Create Workout Types</ContentTitle>
            </template>

            <template #content>
                <ValidationErrors class="mb-4" />

                <form @submit.prevent="submit" autocomplete="off">
                    <div>
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

                    <div class="mt-4">
                        <Label for="description" value="Description" />
                        <Textarea
                            id="description"
                            class="mt-1 block w-full"
                            v-model="form.description"
                        />
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
                </form>
            </template>
        </ContentBox>
    </AuthenticatedLayout>
</template>