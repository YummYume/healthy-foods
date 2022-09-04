<script>
    import { page } from "@inertiajs/inertia-svelte";
    import { SvelteToast, toast } from "@zerodevx/svelte-toast";
    import { Link } from "@inertiajs/inertia-svelte";
    import Icon from "svelte-icons-pack/Icon.svelte";
    import AiOutlineMenu from "svelte-icons-pack/ai/AiOutlineMenu";
    import IoFastFood from "svelte-icons-pack/io/IoFastFood";
    import AiFillHome from "svelte-icons-pack/ai/AiFillHome";
    import AiOutlineUnorderedList from "svelte-icons-pack/ai/AiOutlineUnorderedList";
    import FaBrandsApple from "svelte-icons-pack/fa/FaBrandsApple";
    import { LightSwitch } from "@brainandbones/skeleton";
    import { Drawer } from "@brainandbones/skeleton";
    import { GradientHeading } from "@brainandbones/skeleton";
    import CgClose from "svelte-icons-pack/cg/CgClose";
    import { Dialog } from "@brainandbones/skeleton";
    import { _, isLoading, locale, locales } from "svelte-i18n";
    import Select from "svelte-select";
    import axios from "axios";
    import { RingLoader } from "svelte-loading-spinners";
    import { blur } from "svelte/transition";

    import { title, description } from "./stores/seo";
    import { drawer } from "./stores/drawer";

    const menuItems = [
        { label: "menu.home", path: "/", prefix: "index", icon: AiFillHome },
        { label: "menu.foods", path: "/food", prefix: "food_", icon: IoFastFood },
        { label: "menu.categories", path: "/category", prefix: "category_", icon: AiOutlineUnorderedList },
        { label: "menu.brands", path: "/brand", prefix: "brand_", icon: FaBrandsApple }
    ];
    const localeNames = {
        en: "English",
        "fr-FR": "Français"
    };

    $: route = $page.props.route;
    $: displayFlashMessages($page.props.flashMessages);
    $: availableLocales = $locales.map((l) => ({ value: l, label: localeNames[l] ?? l }));
    $: currentLocale = { value: $locale, label: localeNames[$locale] ?? $locale };
    $: handleLocaleChange($locale);

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

    function handleLocaleChange(locale) {
        window.localStorage.setItem("locale", locale);
        axios.defaults.headers.common["i18n-locale"] = locale;
    }
</script>

<svelte:head>
    <title>{$title}</title>
    <meta name="description" content={$description} />
    <link rel="shortcut icon" src="@assets/favicon.ico" type="image/x-icon" />
</svelte:head>

<div id="app-layout" class="min-h-screen w-full bg-surface-100 dark:bg-surface-800 text-surface-700 dark:text-surface-300">
    {#if !$isLoading}
        <div class="flex" transition:blur>
            <Drawer visible={drawer} fixed="left">
                <div slot="header" class="relative">
                    <GradientHeading
                        class="w-full text-center text-4xl p-4"
                        tag="h3"
                        direction="bg-gradient-to-b"
                        from="from-accent-500"
                        to="to-primary-500"
                    >
                        Healthy food
                    </GradientHeading>
                    <div class="absolute top-2 right-2 fill-surface-200 lg:hidden" on:click={drawer.drawerClose}>
                        <Icon src={CgClose} size="1rem" />
                    </div>
                </div>
                <div class="p-2" slot="main">
                    {#each menuItems as menuItem}
                        {@const selected = route?.startsWith(menuItem.prefix)}
                        <Link
                            class={`flex items-center space-x-1 rounded-md px-2 py-3 my-2 transition-all hover:transition-all ${
                                selected
                                    ? "bg-surface-300 text-primary-700 fill-primary-700"
                                    : "dark:text-primary-200 dark:fill-primary-200 hover:bg-surface-300 hover:text-primary-700 dark:hover:text-primary-700 hover:fill-primary-700 dark:hover:fill-primary-700"
                            }`}
                            href={menuItem.path}
                        >
                            <Icon src={menuItem.icon} size="1.1em" />
                            <span>{$_(menuItem.label)}</span>
                        </Link>
                    {/each}
                </div>
                <div slot="footer" class="flex items-center gap-6 p-5">
                    <LightSwitch />
                    <div class="!my-0 grow form-group">
                        <label for="locale" class="tracking-wide leading-4 mb-1">{$_("common.language")}</label>
                        <Select
                            id="locale"
                            items={availableLocales}
                            value={currentLocale}
                            placeholder={$_("common.language")}
                            isClearable={false}
                            on:select={(e) => locale.set(e.detail.value)}
                        />
                    </div>
                </div>
            </Drawer>
            <div class="w-screen min-h-screen">
                <div class="flex relative">
                    <main class="w-full min-h-screen p-4">
                        <slot />
                        <div class="wrap">
                            <SvelteToast options={{ pausable: true, theme: { "--toastBarBackground": "rgb(var(--color-surface-200))" } }} />
                        </div>
                    </main>
                </div>
                <div
                    on:click={drawer.drawerOpen}
                    class="fixed bottom-5 right-5 bg-primary-700 rounded-full p-3 opacity-75 fill-surface-200 lg:hidden"
                >
                    <Icon src={AiOutlineMenu} size="1.8rem" />
                </div>
            </div>
        </div>
    {:else}
        <div class="fixed flex flex-col text-center align-middle gap-3 justify-center top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            <RingLoader size="120" color="rgb(var(--color-accent-500))" unit="px" duration="2s" />
            <span class="text-lg tracking-wide leading-4 text-primary-500">Loading...</span>
        </div>
    {/if}
    <Dialog
        backdrop="bg-primary-600/50 dark:bg-primary-400/50"
        blur="backdrop-blur-sm"
        card="bg-primary-300 dark:bg-primary-700 text-surface-700 dark:text-surface-300"
        duration={250}
    />
</div>

<style lang="scss">
    // Toasts
    .wrap {
        --toastContainerTop: auto;
        --toastContainerRight: 2rem;
        --toastContainerBottom: 2rem;
        --toastContainerLeft: auto;
    }
    @media screen and (max-width: 640px) {
        .wrap {
            --toastContainerRight: 1rem;
        }
    }

    :global(.info) {
        --toastBackground: rgb(var(--color-primary-400));
    }
    :global(.warn) {
        --toastBackground: rgb(var(--color-warning-200));
    }
    :global(.success) {
        --toastBackground: rgb(var(--color-accent-800));
    }
    :global(.error) {
        --toastBackground: rgb(var(--color-warning-800));
    }
</style>
