<script setup>
import { computed } from "vue";

const emit = defineEmits(["update:checked"]);

const props = defineProps({
    checked: {
        type: [Array, Boolean],
        default: false,
    },
    value: {
        default: null,
    },
});

const proxyChecked = computed({
    get() {
        return props.checked;
    },

    set(val) {
        emit("update:checked", val);
    },
});
</script>

<template>
    <input
        type="checkbox"
        :value="value"
        v-model="proxyChecked"
        class="
            rounded
            border-gray-300
            text-indigo-600
            shadow-sm
            focus:border-indigo-300
            focus:ring
            focus:ring-indigo-200
            focus:ring-opacity-50
            dark:bg-zinc-900
            dark:border-zinc-500
            dark:hover:bg-blue-400
            dark:focus:border-0
            dark:focus:bg-blue-400
            dark:focus:border-blue-400
            dark:focus:ring-1
            dark:focus:ring-blue-300
            dark:focus:ring-opacity-50
        "
    />
</template>
