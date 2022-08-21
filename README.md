# Svelte & Inertia.js Demo

## What is it

Demo for Svelte and Inertia.js. Folders `react` and `svelte` both contain the same app (a simple form with errors) to showcase the differences between React and Svelte. Both apps are using Vite.
The `symfony-inertia-svelte` folder contains the main app for this demo. It is built like a simple Symfony app, except you don't actually use `.twig` files, but `.svelte`, making it a SPA just like a classic Svelte app. This is done thanks to Inertia.js. The app uses Webpack Encore (with Hot Reload enabled!).

## How to use

-   Run `make start` for a first startup, otherwise `make up`. That's it. See the **commands** section below for more.

## Ports

App should run on `localhost`.

| Port  | Usage                     | Note                                    |
| ----- | ------------------------- | --------------------------------------- |
| :3000 | React Vite App            |                                         |
| :4000 | Svelte Vite App           |                                         |
| :443  | Symfony App               | Uses **https**                          |
| :80   | Redirects to :443         |                                         |
| :5000 | Webpack Encore dev server | Used for hot reloading (uses **https**) |
| :8080 | PHPMyAdmin                |                                         |
| :1080 | Mailcatcher               |                                         |

## Commands (Makefile)

| Command                  | Usage                                                                   | Note                                                  |
| ------------------------ | ----------------------------------------------------------------------- | ----------------------------------------------------- |
| start                    | Builds and starts containers, then installs dependencies and creates db | Should only be used for fresh starts (will delete db) |
| up                       | Builds and starts containers                                            |                                                       |
| stop                     | Stops and kills running containers                                      |                                                       |
| rm                       | Removes stopped containers                                              |                                                       |
| vendor                   | Installs vendor dependencies for the Symfony app                        |                                                       |
| ssh                      | Use to sh into the Symfony App container                                | Container `app`                                       |
| shh-react                | Use to sh into the React App container                                  | Container `react`                                     |
| shh-react                | Use to sh into the React App container                                  | Container `react`                                     |
| shh-svelte               | Use to sh into the Svelte App container                                 | Container `svelte`                                    |
| db                       | Deletes (if it exist) the current db, then recreates it (with fixtures) |                                                       |
| perm                     | Gives rights to Symfony App files                                       | Useful after a `make:entity` for example              |
| cc                       | Clears Symfony App cache and then warmup                                |                                                       |
| sync-react-node-modules  | Copies the `node_modules` folder from the React App to host             |                                                       |
| sync-svelte-node-modules | Copies the `node_modules` folder from the Svelte App to host            |                                                       |
| sync-node-modules        | Copies the `node_modules` folder from the Symfony App to host           |                                                       |
| sync-all                 | Copies all `node_modules` folders to host                               |                                                       |

The sync commands are here because node_modules are not shared with the host (to avoid startup problems), but you might still want them for your IDE to use.

## About Vite

I used Vite to get a quick up-and-running app for React/Svelte with Typescript. It is however, in my opinion, not as good for a Symfony app.
I made a Vite version for the Symfony app but it is still extremely experimental, subjects to bug, and hot reload doesn't even work. Webpack Encore is definitely the best option for Symfony apps as of right now (especially with newer bundles like Inertia.js or Svelte).

## About the Demo itself

This demo is used for showcasing Svelte and see how it performs compared to React and other JS Frameworks. It is also used to demonstrate what Inertia.js is and how it can be used to greatly enhance Symfony apps.

## Links

| Link                                                                                                                      | About                                                                        |
| ------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------- |
| [Google Slides](https://docs.google.com/presentation/d/1qtmrZN4C5-8_JYXCTaNd4TVAsYDB0fTi3GWwg984U3Y/edit?usp=sharing)     | Google Slides used during the presentation                                   |
| [Svelte Documentary](https://www.youtube.com/watch?v=kMlkCYL9qo0)                                                         | A very good documentary about Svelte and its origins                         |
| [Svelte Tutorial](https://svelte.dev/tutorial/basics)                                                                     | Official Svelte tutorial, covering every basic you need to know about Svelte |
| [SvelteKit](https://kit.svelte.dev/)                                                                                      | The SvelteKit website                                                        |
| [Svelte Loader](https://github.com/sveltejs/svelte-loader)                                                                | Webpack loader for Svelte                                                    |
| [Made With Svelte](https://madewithsvelte.com/)                                                                           | Site referencing sites, packages, etc... Made with Svelte                    |
| [Skeleton UI](https://skeleton.brainandbonesllc.com/)                                                                     | Svelte UI component library                                                  |
| [Lofi and Games](https://www.lofiandgames.com/)                                                                           | Website to play relaxing games, made with Svelte                             |
| [Svelkedex](https://svelkedex.com/#/welcome)                                                                              | Website to browse the Pokedex, made with Svelte                              |
| [Photon](https://photon-alexwarnes.vercel.app/showcase)                                                                   | 3D games made with Svelte and Threlte ~ Svelte-cubed                         |
| [Paimon.moe](https://paimon.moe/)                                                                                         | Genshin Impact companion, made with Svelte                                   |
| [Svelte Society](https://sveltesociety.dev/)                                                                              | Website containing many Svelte resources                                     |
| [Svelte Material UI](https://sveltematerialui.com/)                                                                       | Material UI for Svelte                                                       |
| [Inertia.js](https://inertiajs.com/)                                                                                      | The Inertia.js website                                                       |
| [Inertia.js Symfony bundle](https://github.com/rompetomp/inertia-bundle)                                                  | Symfony bundle for Inertia.js                                                |
| [Stack Overflow Survey](https://insights.stackoverflow.com/survey/2021#most-loved-dreaded-and-wanted-webframe-love-dread) | Stack Overflow survey about the most loved web-framework                     |
| [State of JS - Front-End Frameworks](https://2021.stateofjs.com/en-US/libraries/front-end-frameworks/)                    | Stats & graphs about front-end frameworks in 2021                            |
| [JS Framework Benchmark](https://shorturl.at/DORX1)                                                                       | Benchmarks of JS frameworks (React, Vue, Svelte & Angular)                   |
