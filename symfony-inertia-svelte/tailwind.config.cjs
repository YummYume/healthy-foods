/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './assets/**/*.html',
        './assets/**/*.svelte',
        './assets/**/*.css',
        './node_modules/@brainandbones/skeleton/**/*.{html,js,svelte,ts}'
    ],
    theme: {
        extend: {},
    },
    darkMode: 'class',
    plugins: [
        require('@tailwindcss/forms'),
        require('@brainandbones/skeleton/tailwind.cjs')
    ],
}
