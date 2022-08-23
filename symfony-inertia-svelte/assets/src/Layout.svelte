<script>
    import { page } from "@inertiajs/inertia-svelte";
    import { SvelteToast, toast } from "@zerodevx/svelte-toast";
    import { fly } from "svelte/transition";
    import { Link } from "@inertiajs/inertia-svelte";
    import Icon from "svelte-icons-pack/Icon.svelte";
    import AiOutlineMenu from "svelte-icons-pack/ai/AiOutlineMenu";
    import IoFastFood from "svelte-icons-pack/io/IoFastFood";
    import AiFillHome from "svelte-icons-pack/ai/AiFillHome";
    import AiOutlineUnorderedList from "svelte-icons-pack/ai/AiOutlineUnorderedList";
    import FaBrandsApple from "svelte-icons-pack/fa/FaBrandsApple";

    import { title, description } from "./stores/seo";

    const menuItems = [
        { label: "Home", path: "/", prefix: "index", icon: AiFillHome },
        { label: "Foods", path: "/food", prefix: "food_", icon: IoFastFood },
        { label: "Categories", path: "/category", prefix: "category_", icon: AiOutlineUnorderedList },
        { label: "Brands", path: "/brand", prefix: "brand_", icon: FaBrandsApple }
    ];

    let menuOpen = true;

    $: route = $page.props.route;
    $: displayFlashMessages($page.props.flashMessages);

    function displayFlashMessages(flashMessages) {
        if (!flashMessages instanceof Object) {
            return;
        }

        for (const category in flashMessages) {
            const flashMessage = flashMessages[category];

            if (!Array.isArray(flashMessage)) {
                continue;
            }

            flashMessage.forEach((flash) => toast.push(flash, { classes: [category] }));
        }
    }
</script>

<svelte:head>
    <title>{$title}</title>
    <meta name="description" content={$description} />
    <link rel="shortcut icon" src="@assets/favicon.ico" type="image/x-icon" />
</svelte:head>

<div id="app-layout" class="min-h-screen w-full bg-gray-100 text-gray-700">
    <header class="flex w-full items-center justify-between border-b-2 border-gray-200 bg-white p-2" style="height: 5vh;">
        <!-- logo -->
        <div class="flex items-center space-x-2">
            <button name="open-close-menu" type="button" class="text-3xl" on:click={() => (menuOpen = !menuOpen)}>
                <Icon src={AiOutlineMenu} />
            </button>
            <div>Menu</div>
        </div>
        <div>
            <h1>{$title}</h1>
        </div>
    </header>
    <div class="flex relative">
        {#if menuOpen}
            <aside
                transition:fly|local={{ x: -250 }}
                class="flex flex-col space-y-2 border-r-2 border-gray-200 bg-white p-2 w-full absolute md:static md:w-72"
                style="min-height: 95vh"
            >
                {#each menuItems as menuItem}
                    <Link
                        class={`flex items-center space-x-1 rounded-md px-2 py-3 ${
                            route?.startsWith(menuItem.prefix) ? "bg-gray-100 text-blue-600" : "hover:bg-gray-100 hover:text-blue-600"
                        }`}
                        href={menuItem.path}
                    >
                        <Icon src={menuItem.icon} size="1.2em" />
                        <span>{menuItem.label}</span>
                    </Link>
                {/each}
            </aside>
        {/if}
        <main class="w-full p-4">
            <slot />
            <div class="wrap">
                <SvelteToast options={{ pausable: true, theme: { "--toastBarBackground": "white" } }} />
            </div>
        </main>
    </div>
</div>

<style lang="scss">
    // Toasts
    .wrap {
        --toastContainerTop: auto;
        --toastContainerRight: 2rem;
        --toastContainerBottom: 2rem;
        --toastContainerLeft: auto;
    }
    :global(.info) {
        --toastBackground: cyan;
    }
    :global(.warn) {
        --toastBackground: yellow;
    }
    :global(.success) {
        --toastBackground: green;
    }
    :global(.error) {
        --toastBackground: red;
    }
</style>
