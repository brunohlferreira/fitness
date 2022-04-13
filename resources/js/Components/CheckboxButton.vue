<script setup>
defineProps({
    selected: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <button
        class="
            px-2
            py-2
            mr-2
            mt-2
            border
            rounded-md
            dark:border-blue-500
            dark:hover:bg-blue-400
            dark:active:bg-blue-600
            dark:focus:border-blue-600
        "
        :class="selected ? 'bg-blue-500' : ''"
        @click.prevent="selected = !selected"
    >
        <slot />
    </button>
</template>
