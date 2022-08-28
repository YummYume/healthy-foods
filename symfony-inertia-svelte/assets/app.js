import { Inertia } from "@inertiajs/inertia"; // This has to be here for Inertia to be imported in the app
import { createInertiaApp } from '@inertiajs/inertia-svelte';
import { InertiaProgress } from '@inertiajs/progress';
import { register, init, getLocaleFromNavigator } from 'svelte-i18n';

import Layout from './src/Layout.svelte';

import './assets/css/app.css';
import './assets/css/theme.css';

/**
 * Imports the given page component from the page record.
 */
function resolvePageComponent(name, pages) {
    for (const path in pages) {
        if (path.endsWith(`/Pages/${name}.svelte`)) {
            return typeof pages[path] === 'function'
                ? pages[path]()
                : pages[path];
        }
    }

    throw new Error(`Page not found: ${name}`);
}

const locales = [
    { locale: 'en', file: 'en' },
    { locale: 'fr-FR', file: 'fr' }
];

let initialLocale = window.localStorage.getItem('locale');

locales.forEach((l) => {
    register(l.locale, () => import(`./translations/parsed/${l.file}.json`));
})

if ('null' === initialLocale || null === initialLocale || !locales.some((l) => l.locale === initialLocale)) {
    initialLocale = getLocaleFromNavigator();
}

init({
    fallbackLocale: 'en',
    initialLocale,
});

InertiaProgress.init({ delay: 250, color: 'rgb(var(--color-primary-500))' });

createInertiaApp({
    resolve: name => {
        return resolvePageComponent(name, import.meta.glob('./src/Pages/**/*.svelte')).then((module) => {
            return module.layout ? module : Object.assign({ layout: Layout }, module);
        });
    },
    setup({ el, App, props }) {
        new App({ target: el, props });
    },
});
