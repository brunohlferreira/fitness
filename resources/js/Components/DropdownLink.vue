<script setup>
const props = defineProps({
    active: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <Link
        class="
            block
            w-full
            px-4
            py-2
            text-left text-sm
            leading-5
            focus:outline-none focus:bg-gray-100
            transition
            duration-150
            ease-in-out
            dark:bg-zinc-700
        "
        :class="
            props.active
                ? ' text-blue-500 hover:text-blue-500'
                : ' text-gray-700 dark:text-neutral-200 hover:text-blue-500 dark:hover:text-blue-500'
        "
    >
        <slot />
    </Link>
</template>
