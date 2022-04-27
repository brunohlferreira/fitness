<script setup>
defineProps({
    type: {
        type: String,
        default: "submit",
    },
});
</script>

<template>
    <button
        :type="type"
        class="
            inline-flex
            items-center
            px-4
            py-2
            border border-transparent
            rounded-md
            font-semibold
            text-xs text-white
            uppercase
            tracking-widest
            focus:outline-none focus:border-gray-900 focus:shadow-outline-gray
            transition
            ease-in-out
            duration-150
            bg-blue-500
            hover:bg-blue-600
            active:bg-blue-500
            focus:border-blue-500
        "
    >
        <slot />
    </button>
</template>
