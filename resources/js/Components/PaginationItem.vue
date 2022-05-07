<script setup>
const props = defineProps({
    icon: Object,
    url: Object,
    label: Object,
});
</script>

<template>
    <span v-if="!url" class="p-2 border-1">
        <FontAwesomeIcon v-if="icon" :icon="icon"></FontAwesomeIcon>
        <span v-else>{{ label }}</span>
    </span>
    <Link v-else :href="url" class="p-2 border-1 hover:text-blue-500">
        <FontAwesomeIcon v-if="icon" :icon="icon"></FontAwesomeIcon>
        <span v-else>{{ label }}</span>
    </Link>
</template>