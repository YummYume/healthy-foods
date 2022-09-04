import { createInertiaApp } from '@inertiajs/inertia-svelte';
import { InertiaProgress } from '@inertiajs/progress';
import { register, init, getLocaleFromNavigator } from 'svelte-i18n';

import Layout from './src/Layout.svelte';

import './assets/css/app.css';
import './assets/css/theme.css';

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
        const page = require(`./src/Pages/${name}.svelte`);
        page.layout = page.layout || Layout;
        return page;
    },
    setup({ el, App, props }) {
        new App({ target: el, props });
    },
});
