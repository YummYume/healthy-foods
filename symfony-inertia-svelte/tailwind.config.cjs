/** @type {import('tailwindcss').Config} */
module.exports = {
    purge: [
        './assets/**/*.html',
        './assets/**/*.svelte',
        './assets/**/*.css',
    ],
    content: [
        './assets/**/*.svelte',
        './node_modules/@brainandbones/skeleton/**/*.{html,js,svelte,ts}'
    ],
    theme: {
        extend: {},
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@brainandbones/skeleton/tailwind.cjs')
    ],
}
