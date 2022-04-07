<script setup>
import { ref, inject } from "vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";

const showingNavigationDropdown = ref(false);

const toggleTheme = function () {
    darkMode.value = !darkMode.value;
    if (darkMode.value) {
        localStorage.theme = "dark";
        document.documentElement.classList.add("dark");
    } else {
        localStorage.theme = "light";
        document.documentElement.classList.remove("dark");
    }
};

const darkMode = ref(
    !(
        localStorage.theme === "dark" ||
        (!("theme" in localStorage) &&
            window.matchMedia("(prefers-color-scheme: dark)").matches)
    )
);
toggleTheme();

const navPages = [
    {
        title: "Dashboard",
        component: "Dashboard",
        href: "/dashboard",
        icon: "chart-line", //calendar-days
    },
    {
        title: "Workouts",
        component: "Workouts",
        href: "/workouts",
        icon: "dumbbell",
    },
    {
        title: "Presets",
        component: "Presets",
        href: "/workout-presets",
        icon: "shoe-prints",
    },
    {
        title: "Exercises",
        component: "Exercises",
        href: "/exercises",
        icon: "person-running",
    },
];
</script>

<template>
    <div
        class="
            flex flex-col
            h-screen
            overflow-hidden
            bg-gray-100
            dark:bg-zinc-900
            text-gray-700
            dark:text-neutral-200
        "
    >
        <header class="w-full bg-white shadow dark:bg-zinc-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <h1 class="font-bold text-xl py-5">Fitness App</h1>
                    </div>

                    <div class="flex items-center ml-6">
                        <div class="ml-3 relative">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="
                                                inline-flex
                                                items-center
                                                my-2
                                                border border-transparent
                                                text-sm
                                                leading-4
                                                font-medium
                                                rounded-md
                                                text-gray-500
                                                bg-white
                                                hover:text-gray-700
                                                focus:outline-none
                                                transition
                                                ease-in-out
                                                duration-150
                                                dark:bg-zinc-800
                                                dark:text-neutral-200
                                            "
                                        >
                                            {{ $page.props.auth.user.name }}

                                            <svg
                                                class="ml-2 -mr-0.5 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink
                                        :href="route('backoffice.dashboard')"
                                    >
                                        Backoffice
                                    </DropdownLink>
                                    <DropdownLink
                                        @click.prevent="toggleTheme"
                                        as="button"
                                    >
                                        {{
                                            darkMode
                                                ? "Apply Light Theme"
                                                : "Apply Dark Theme"
                                        }}
                                    </DropdownLink>
                                    <DropdownLink
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                    >
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-scroll">
            <div class="max-w-7xl mx-auto sm:px-6 p-4">
                <slot />
            </div>
        </main>

        <nav
            class="
                w-full
                text-xs
                sm:text-sm
                md:text-md
                bg-white
                shadow
                dark:bg-zinc-800
            "
        >
            <div class="max-w-7xl mx-auto sm:px-6 flex justify-between">
                <NavLink
                    v-for="(navPage, index) in navPages"
                    :key="index"
                    :href="navPage.href"
                    :active="$page.component == navPage.component"
                >
                    <FontAwesomeIcon
                        :icon="navPage.icon"
                        class="block w-4 h-4 mb-2 mx-auto"
                    ></FontAwesomeIcon>
                    {{ navPage.title }}
                </NavLink>
            </div>
        </nav>
    </div>
</template>
