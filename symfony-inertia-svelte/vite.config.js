import { defineConfig } from "vite";
import symfonyPlugin from "vite-plugin-symfony";
import { svelte } from "@sveltejs/vite-plugin-svelte";
import mkcert from 'vite-plugin-mkcert';
import autoPreprocess from 'svelte-preprocess';
import path from 'path';

export default defineConfig(({ command }) => {
    const isDev = 'serve' === command;

    return {
        plugins: [
            symfonyPlugin(),
            svelte({
                preprocess: autoPreprocess(),
                experimental: {
                    prebundleSvelteLibraries: true
                },
                hot: isDev,
                emitCss: !isDev
            }),
            mkcert(),
        ],
        css: {
            postCss: {
                plugins: {
                    tailwindcss: {},
                    autoprefixer: {},
                },
            },
        },
        resolve: {
            alias: {
                '@app': path.resolve('assets/src'),
                '@stores': path.resolve('assets/src/stores'),
                '@assets': path.resolve('assets/assets')
            }
        },
        optimizeDeps: {
            include: ['@inertiajs/inertia'],
        },
        root: ".",
        base: "/build/",
        publicDir: '/public',
        build: {
            manifest: true,
            emptyOutDir: true,
            assetsDir: "",
            outDir: "./public/build",
            sourcemap: isDev,
            rollupOptions: {
                input: {
                    app: "./assets/app.js"
                },
            }
        },
        server: {
            port: 5173,
            https: true,
            cors: true,
            origin: 'https://localhost',
            strictPort: true,
            hmr: false
        },
    }
});