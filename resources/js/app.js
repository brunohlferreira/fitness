require('./bootstrap');

import { createApp, h, provide } from 'vue';
import { createInertiaApp, Head, Link } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faChartLine, faCalendarDays, faDumbbell, faPersonRunning, faPlus, faPencil, faTrashCan, faPaste, faClipboard, faListCheck } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faChartLine, faCalendarDays, faDumbbell, faPersonRunning, faPlus, faPencil, faTrashCan, faPaste, faClipboard, faListCheck);

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
            .mixin({ methods: { route } })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });