<template>
    <div>
        <div class="flex justify-between px-2 dark:text-neutral-200">
            <slot name="title" />
            <slot name="actions" />
        </div>

        <div
            class="
                mt-2
                p-4
                overflow-hidden
                rounded-md
                bg-white
                shadow
                text-gray-700
                dark:bg-zinc-800 dark:shadow-zinc-700 dark:text-neutral-200
            "
        >
            <slot name="content" />
        </div>
    </div>
</template>