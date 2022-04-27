<script setup>
defineProps({
    links: Object,
    meta: Object,
});
</script>

<template>
    <div class="flex flex-col items-center mt-4">
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

        <div v-if="meta.last_page > 1" class="inline-flex mt-2 xs:mt-0">
            <Link
                :href="links.prev"
                class="
                    py-2
                    px-4
                    text-sm
                    font-medium
                    rounded-l
                    hover:text-blue-500
                    dark:border-gray-700
                "
            >
                Prev
            </Link>
            <Link
                :href="links.next"
                class="
                    py-2
                    px-4
                    text-sm
                    font-medium
                    rounded-r
                    border-0 border-l border-gray-700
                    hover:text-blue-500
                    dark:border-gray-700
                "
            >
                Next
            </Link>
        </div>
    </div>
</template>