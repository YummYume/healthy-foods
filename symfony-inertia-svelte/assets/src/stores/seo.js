import { writable } from 'svelte/store';

const defaultTitle = 'Healthy Food';
const defaultDescription = 'Your best friend for finding healthy food.';

function createTitle() {
    const { subscribe, set } = writable(defaultTitle);

    return {
        subscribe,
        set: (title) => set(`${title} • ${defaultTitle}`),
        clear: () => set(defaultTitle)
    }
}

function createDescription() {
    const { subscribe, set } = writable(defaultDescription);

    return {
        subscribe,
        set,
        clear: () => set(defaultDescription)
    }
}

export const title = createTitle();
export const description = createDescription();
