import { Inertia } from "@inertiajs/inertia";

export function deleteEntry(url) {
    if (!window.confirm("You are about to permanently delete this entry. Do you want to proceed?")) {
        return;
    }
    window.axios
        .delete(url)
        .then(response => {
            Inertia.visit(window.location.pathname + window.location.search, { preserveScroll: true });
        })
        .catch(errors => {
            window.alert('Something went wrong. Please try again.');
        });
};