<script setup>
defineProps({
    rows: Array,
    actions: {
        type: Boolean,
        default: false,
    },
    viewUrl: {
        type: String,
        default: '',
    },
    editUrl: {
        type: String,
        default: '',
    },
    deleteFunction: Function,
});
</script>

<template>
    <div class="shadow-md sm:rounded-lg">
        <table class="w-full table-auto text-sm text-left">
            <tbody>
                <tr
                    v-for="row in rows"
                    :key="row.id"
                    class="border-b dark:border-gray-700"
                >
                    <td class="pl-6 py-4 font-medium">
                        <Link
                            v-if="row.route"
                            :href="route(row.route)"
                            class="row:text-blue-500"
                            >{{ row.name }}</Link
                        >
                        <Link
                            v-else-if="viewUrl.length"
                            :href="viewUrl + '/' + row.id + '/show'"
                            class="row:text-blue-500"
                            >{{ row.name }}</Link
                        >
                        <span v-else>{{ row.name }}</span>
                    </td>
                    <td v-if="actions" class="pr-6 py-4 text-right">
                        <Link
                            v-if="editUrl.length"
                            :href="editUrl + '/' + row.id + '/edit'"
                            class="inline-block ml-4"
                            ><FontAwesomeIcon icon="pencil"></FontAwesomeIcon
                        ></Link>
                        <Link
                            @click.prevent="deleteFunction(row.id)"
                            class="inline-block ml-4"
                            ><FontAwesomeIcon icon="trash-can"></FontAwesomeIcon
                        ></Link>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>