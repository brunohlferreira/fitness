require("./bootstrap");

import { createApp, h, ref, provide } from "vue";
import { createInertiaApp, Head, Link } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
import { library } from "@fortawesome/fontawesome-svg-core";
import { faChartLine, faDumbbell, faShoePrints, faPersonRunning, faPlus, faMinus, faPencil, faTrashCan, faPaste, faClipboard, faListCheck } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

library.add(faChartLine, faDumbbell, faShoePrints, faPersonRunning, faPlus, faMinus, faPencil, faTrashCan, faPaste, faClipboard, faListCheck);

const appName = window.document.getElementsByTagName("title")[0]?.innerText || "Fitness App";

const darkMode = ref((localStorage.theme === "dark" || (!("theme" in localStorage) && window.matchMedia("(prefers-color-scheme: dark)").matches)));
if (darkMode.value) {
    localStorage.theme = "dark";
    document.documentElement.classList.add("dark");
} else {
    localStorage.theme = "light";
    document.documentElement.classList.remove("dark");
}

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .component("FontAwesomeIcon", FontAwesomeIcon)
            .component("Head", Head)
            .component("Link", Link)
            .provide("darkMode", darkMode)
            .mixin({ methods: { route } })
            .mount(el);
    },
});

InertiaProgress.init({ color: "#4B5563" });