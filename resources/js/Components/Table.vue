<script setup>
defineProps({
    rows: Array,
    canUpdate: {
        type: Boolean,
        default: false,
    },
    canDelete: {
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
                            class="hover:text-blue-500"
                            >{{ row.name }}</Link
                        >
                        <Link
                            v-else-if="viewUrl.length"
                            :href="viewUrl.replace(/%d/, row.id)"
                            class="hover:text-blue-500"
                            >{{ row.name }}</Link
                        >
                        <span v-else>{{ row.name }}</span>
                        <span v-if="row.info" class="block text-xs text-gray-400">{{ row.info }}</span>
                    </td>
                    <td v-if="canUpdate || canDelete" class="pr-6 py-4 text-right">
                        <Link
                            v-if="canUpdate && editUrl.length"
                            :href="editUrl.replace(/%d/, row.id)"
                            class="inline-block ml-4 hover:text-blue-500"
                            ><FontAwesomeIcon icon="pencil"></FontAwesomeIcon
                        ></Link>
                        <Link
                            v-if="canDelete"
                            @click.prevent="deleteFunction(row.id)"
                            class="inline-block ml-4 hover:text-blue-500"
                            ><FontAwesomeIcon icon="trash-can"></FontAwesomeIcon
                        ></Link>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>