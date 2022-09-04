<script>
    import { Inertia } from "@inertiajs/inertia";
    import { Button, GradientHeading } from "@brainandbones/skeleton";
    import Icon from "svelte-icons-pack/Icon.svelte";
    import AiFillHome from "svelte-icons-pack/ai/AiFillHome";
    import { _ } from "svelte-i18n";

    import { title, description } from "@stores/seo";

    export let status;

    $: exceptionTitle = {
        503: "exception.503.title",
        500: "exception.500.title",
        404: "exception.404.title",
        403: "exception.403.title"
    }[status];

    $: exceptionDescription = {
        503: "exception.503.description",
        500: "exception.500.description",
        404: "exception.404.description",
        403: "exception.403.description"
    }[status];

    $: exceptionTitle, title.set($_(exceptionTitle));
    $: exceptionDescription, description.set($_(exceptionDescription));
</script>

<GradientHeading
    class="w-full text-center mb-10 text-4xl"
    tag="h2"
    direction="bg-gradient-to-b"
    from="from-warning-300"
    to="to-primary-600"
>
    {$_(exceptionTitle)}
</GradientHeading>

<p class="text-lg tracking-wide leading-7 my-10">{$_(exceptionDescription)}</p>

<Button
    size="base"
    background="bg-primary-800"
    color="text-surface-200"
    ring="ring-transparent"
    weight="ring-none"
    rounded="rounded-full"
    width="w-auto"
    on:click={() => Inertia.visit("/")}
>
    <span slot="lead" class="fill-surface-200"><Icon src={AiFillHome} /></span>
    <span>{$_("exception.home_page")}</span>
</Button>
