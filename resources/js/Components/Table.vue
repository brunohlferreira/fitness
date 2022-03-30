<script setup>
defineProps({
    rows: Array,
    actions: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div class="overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left">
            <tbody>
                <tr
                    v-for="row in rows"
                    :key="row.id"
                    class="border-b dark:border-gray-700"
                >
                    <td class="px-6 py-4 font-medium whitespace-nowrap">
                        <Link
                            v-if="row.route"
                            :href="route(row.route)"
                            class="row:text-blue-500"
                            >{{ row.name }}</Link
                        >
                        <span v-else>{{ row.name }}</span>
                    </td>
                    <td v-if="actions" class="px-6 py-4 text-right">
                        <Link
                            :href="'/backoffice/body-parts/' + row.id + '/edit'"
                            class="inline-block mx-2"
                            ><FontAwesomeIcon icon="pencil"></FontAwesomeIcon
                        ></Link>
                        <Link
                            @click.prevent="deleteRecord(row.id)"
                            class="inline-block mx-2 mr-0"
                            ><FontAwesomeIcon icon="trash-can"></FontAwesomeIcon
                        ></Link>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>