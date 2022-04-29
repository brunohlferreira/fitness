<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Button from "@/Components/Button.vue";
import GuestLayout from "@/Layouts/Guest.vue";
import Input from "@/Components/Input.vue";
import Label from "@/Components/Label.vue";
import ValidationErrors from "@/Components/ValidationErrors.vue";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post("/users", {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head title="Create User" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>Create User</ContentTitle>
            </template>

            <template #content>
                <ValidationErrors class="mb-4" />

                <form @submit.prevent="submit" autocomplete="off">
                    <div>
                        <div class="grid grid-cols-6 gap-4">
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
                                <Label for="email" value="Email" />
                                <Input
                                    id="email"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.email"
                                    required
                                    autofocus
                                />
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="grid grid-cols-6 gap-4">
                            <div class="col-span-6 sm:col-span-3">
                                <Label for="password" value="Password" />
                                <Input
                                    id="password"
                                    type="password"
                                    class="mt-1 block w-full"
                                    v-model="form.password"
                                    required
                                />
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <Label
                                    for="password_confirmation"
                                    value="Confirm Password"
                                />
                                <Input
                                    id="password_confirmation"
                                    type="password"
                                    class="mt-1 block w-full"
                                    v-model="form.password_confirmation"
                                    required
                                />
                            </div>
                        </div>
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
