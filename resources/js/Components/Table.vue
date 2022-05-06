<script setup>
import { deleteEntry } from "@/Helpers/deleteEntry";

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
        default: "",
    },
    editUrl: {
        type: String,
        default: "",
    },
    deleteUrl: {
        type: String,
        default: "",
    },
});
</script>

<template>
    <div class="-my-4">
        <table class="w-full table-auto text-sm text-left">
            <tbody>
                <tr
                    v-for="row in rows"
                    :key="row.id"
                    class="border-t first:border-0 dark:border-gray-700"
                >
                    <td class="py-4 font-medium">
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
                        <span
                            v-if="row.info"
                            class="block text-xs text-gray-400"
                            >{{ row.info }}</span
                        >
                    </td>
                    <td v-if="canUpdate || canDelete" class="py-4 text-right">
                        <Link
                            v-if="canUpdate && editUrl.length"
                            :href="editUrl.replace(/%d/, row.id)"
                            class="inline-block ml-4 hover:text-blue-500"
                            title="Edit"
                            ><FontAwesomeIcon icon="pencil"></FontAwesomeIcon
                        ></Link>
                        <button
                            v-if="canDelete && deleteUrl.length"
                            @click.prevent="
                                deleteEntry(deleteUrl.replace(/%d/, row.id))
                            "
                            class="inline-block ml-4 hover:text-blue-500"
                            title="Remove"
                        >
                            <FontAwesomeIcon icon="trash-can"></FontAwesomeIcon>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>