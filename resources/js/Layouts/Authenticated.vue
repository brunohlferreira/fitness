<script setup>
import { ref } from 'vue';
import BreezeDropdown from '@/Components/Dropdown.vue';
import BreezeDropdownLink from '@/Components/DropdownLink.vue';
import BreezeNavLink from '@/Components/NavLink.vue';

const showingNavigationDropdown = ref(false);

const toggleTheme = function() {
    darkMode.value = !darkMode.value;
    if (darkMode.value) {
        localStorage.theme = 'dark';
        document.documentElement.classList.add('dark');
    } else {
        localStorage.theme = 'light';
        document.documentElement.classList.remove('dark');
    }
};

const darkMode = ref(!(localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)));
toggleTheme();
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-zinc-900">
        <nav class="bg-white border-b border-gray-100 dark:bg-zinc-800 dark:border-zinc-900 dark:text-neutral-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <h1 class="font-bold text-xl py-5">Fitness App</h1>
                    </div>

                    <div class="flex items-center ml-6">
                        <div class="ml-3 relative">
                            <BreezeDropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150 dark:bg-zinc-800 dark:text-neutral-200">
                                            {{ $page.props.auth.user.name }}

                                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <BreezeDropdownLink @click.prevent="toggleTheme" as="button">
                                        {{ darkMode ? 'Apply Light Theme' : 'Apply Dark Theme' }}
                                    </BreezeDropdownLink>
                                    <BreezeDropdownLink :href="route('logout')" method="post" as="button">
                                        Log Out
                                    </BreezeDropdownLink>
                                </template>
                            </BreezeDropdown>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main>
            <slot />
        </main>

        <nav class="fixed bottom-0 inset-x-0 bg-white border-t border-gray-100 text-xs sm:text-sm md:text-base text-md dark:bg-zinc-800 dark:border-neutral-800 dark:text-neutral-200">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-between">
                <BreezeNavLink href="/dashboard" :active="$page.component == 'Dashboard'">
                    <font-awesome-icon icon="chart-line" class="block w-4 h-4 mb-2 mx-auto"></font-awesome-icon>
                    Dashboard
                </BreezeNavLink>
                <BreezeNavLink href="/calendar" :active="$page.component == 'Calendar'">
                    <font-awesome-icon icon="calendar-days" class="block w-4 h-4 mb-2 mx-auto"></font-awesome-icon>
                    Calendar
                </BreezeNavLink>
                <BreezeNavLink href="/wods" :active="$page.component == 'Wods'">
                    <font-awesome-icon icon="dumbbell" class="block w-4 h-4 mb-2 mx-auto"></font-awesome-icon>
                    Wods List
                </BreezeNavLink>
                <BreezeNavLink href="/exercises" :active="$page.component == 'Exercises'">
                    <font-awesome-icon icon="person-running" class="block w-4 h-4 mb-2 mx-auto"></font-awesome-icon>
                    Exercises
                </BreezeNavLink>
            </div>
        </nav>
    </div>
</template>
