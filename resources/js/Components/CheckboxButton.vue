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
            hover:bg-blue-500
            hover:text-white
        "
        :class="selected ? 'bg-blue-500 text-white' : ''"
        @click.prevent="selected = !selected"
    >
        <slot />
    </button>
</template>
