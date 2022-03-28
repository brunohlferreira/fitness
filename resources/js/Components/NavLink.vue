<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';

const props = defineProps(['active']);

/*const classes = computed(() => props.active
    ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition  duration-150 ease-in-out'
    : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out'
);*/
const classes = computed(() => props.active
    ? 'w-full block py-5 px-3 text-center text-blue-400 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
    : 'w-full block py-5 px-3 text-center focus:text-teal-500 hover:text-blue-400'
);
//
</script>

<template>
    <Link :class="classes">
        <slot />
    </Link>
</template>
