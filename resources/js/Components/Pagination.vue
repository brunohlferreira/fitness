<script setup>
import PaginationItem from "@/Components/PaginationItem.vue";

const props = defineProps({
    links: Object,
    meta: Object,
});
</script>

<template>
    <div v-if="meta && meta.total" class="flex flex-col items-center mt-4">
        <span class="text-sm text-gray-700 dark:text-gray-400">
            <span v-if="meta.last_page == 1">
                Showing all
                <span class="font-semibold">{{ meta.total }}</span> entries
            </span>
            <span v-else>
                Showing
                <span class="font-semibold">{{ meta.from }}</span>
                to
                <span class="font-semibold">{{ meta.to }}</span>
                of
                <span class="font-semibold">{{ meta.total }}</span>
                entries
            </span>
        </span>

        <ul v-if="meta.last_page > 1" class="inline-flex mt-2 text-sm text-gray-700 dark:text-gray-400">
            <li><PaginationItem title="First" :url="meta.current_page > 1 ? links.first : null" icon="angles-left" /></li>
            <li><PaginationItem title="Previous" :url="meta.current_page > 1 ? links.prev : null" icon="angle-left" /></li>
            <li><PaginationItem :label="props.meta.current_page" /></li>
            <li><PaginationItem title="Next" :url="meta.current_page < meta.last_page ? links.next : null" icon="angle-right" /></li>
            <li><PaginationItem title="Last" :url="meta.current_page < meta.last_page ? links.last : null" icon="angles-right" /></li>
        </ul>
    </div>
</template>