import { createInertiaApp } from '@inertiajs/inertia-svelte';
import { InertiaProgress } from '@inertiajs/progress';

import Layout from './src/Layout.svelte';

import './assets/css/app.css';
import './assets/css/theme.css';

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
