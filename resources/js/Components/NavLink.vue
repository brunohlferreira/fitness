<script setup>
import { computed } from "vue";

const props = defineProps(["active"]);

const classes = computed(() =>
    props.active
        ? "w-full block py-5 px-3 text-center text-blue-400"
        : "w-full block py-5 px-3 text-center focus:text-blue-400 hover:text-blue-400"
);
</script>

<template>
    <Link :class="classes">
        <slot />
    </Link>
</template>
