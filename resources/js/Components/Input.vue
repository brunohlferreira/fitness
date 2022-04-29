<script setup>
import { onMounted, ref } from "vue";

defineProps(["modelValue"]);

defineEmits(["update:modelValue"]);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});
</script>

<template>
    <input
        class="
            appearance-none
            border-gray-300
            focus:border-indigo-300
            focus:ring
            focus:ring-indigo-200
            focus:ring-opacity-50
            rounded-md
            shadow-sm
            dark:bg-zinc-900
            dark:border-zinc-500
            dark:focus:border-blue-400
            dark:focus:ring-1
            dark:focus:ring-blue-300
            dark:focus:ring-opacity-50
        "
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    />
</template>

<style scoped>
.dark input:-webkit-autofill,
.dark input:-webkit-autofill:hover,
.dark input:-webkit-autofill:focus,
.dark input:-webkit-autofill:active {
    -webkit-box-shadow: 0 0 0 30px #18181b inset !important;
    -webkit-text-fill-color: #e5e5e5 !important;
}
</style>