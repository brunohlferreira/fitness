<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import ContentTitle from "@/Components/ContentTitle.vue";
import ContentBox from "@/Components/ContentBox.vue";
import Label from "@/Components/Label.vue";
import Select from "@/Components/Select.vue";
import Button from "@/Components/Button.vue";
import ValidationErrors from "@/Components/ValidationErrors.vue";

const props = defineProps({
    role: Object,
    roles: Object,
});

const form = useForm({
    role: props.role.data.id ? props.role.data.id : 0,
});

const submit = () => {
    form.put(`/users/${props.role.data.userId}/role`);
};
</script>

<template>
    <Head title="Edit Role" />

    <AuthenticatedLayout>
        <ContentBox>
            <template #title>
                <ContentTitle>Edit Role</ContentTitle>
            </template>

            <template #content>
                <ValidationErrors class="mb-4" />

                <form @submit.prevent="submit" autocomplete="off">
                    <div>
                        <Label for="role" value="Role" />
                        <Select
                            id="role"
                            class="mt-1 block w-full"
                            v-model="form.role"
                        >
                            <option value="0">None</option>
                            <option
                                v-for="role in roles.data"
                                :key="role.id"
                                :value="role.id"
                            >
                                {{ role.name }}
                            </option>
                        </Select>
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