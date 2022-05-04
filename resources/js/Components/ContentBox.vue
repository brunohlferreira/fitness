<template>
    <div>
        <div class="flex justify-between dark:text-neutral-200">
            <slot name="title" />
            <slot name="actions" />
        </div>
        
        <slot name="subContent" />

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