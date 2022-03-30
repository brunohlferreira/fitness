require('./bootstrap');

import { createApp, h, provide } from 'vue';
import { createInertiaApp, Head, Link } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faChartLine, faCalendarDays, faDumbbell, faPersonRunning, faPlus, faPencil, faTrashCan } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faChartLine, faCalendarDays, faDumbbell, faPersonRunning, faPlus, faPencil, faTrashCan);

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Fitness App';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .component('FontAwesomeIcon', FontAwesomeIcon)
            .component('Head', Head)
            .component('Link', Link)
            .provide('themeBackground', 'bg-gray-100 dark:bg-zinc-900')
            .provide('themeSurface', 'bg-white shadow-md dark:bg-zinc-800 dark:border-zinc-900 dark:text-neutral-200')
            .provide('themeFormLabel', 'text-gray-700 dark:text-neutral-200')
            .provide('themeFormImput', 'border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-zinc-900 dark:border-zinc-500 dark:focus:border-blue-400 dark:focus:ring-1 dark:focus:ring-blue-300 dark:focus:ring-opacity-50')
            .provide('themeFormCheckbox', '')
            .provide('themeButton', 'bg-gray-800 text-white hover:bg-gray-700 active:bg-gray-900 focus:border-gray-900 dark:bg-blue-500 dark:hover:bg-blue-400 dark:active:bg-blue-600 dark:focus:border-blue-600')
            .provide('themeLink', '')
            .mixin({ methods: { route } })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });