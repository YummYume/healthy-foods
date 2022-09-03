<script>
    import { Inertia } from "@inertiajs/inertia";
    import { Button, GradientHeading } from "@brainandbones/skeleton";
    import Icon from "svelte-icons-pack/Icon.svelte";
    import AiFillHome from "svelte-icons-pack/ai/AiFillHome";

    import { title, description } from "@stores/seo";

    export let status;

    $: exceptionTitle = {
        503: "503: Service Unavailable",
        500: "500: Server Error",
        404: "404: Page Not Found",
        403: "403: Forbidden"
    }[status];

    $: exceptionDescription = {
        503: "Sorry, we are doing some maintenance. Please check back soon.",
        500: "Whoops, something went wrong on our servers.",
        404: "Sorry, the page you are looking for could not be found.",
        403: "Sorry, you are forbidden from accessing this page."
    }[status];

    $: title.set(exceptionTitle);
    $: description.set(exceptionDescription);
</script>

<GradientHeading
    class="w-full text-center mb-10 text-4xl"
    tag="h2"
    direction="bg-gradient-to-b"
    from="from-warning-300"
    to="to-primary-600"
>
    {exceptionTitle}
</GradientHeading>

<p class="text-lg tracking-wide leading-7 my-10">{exceptionDescription}</p>

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
    <span>Go to home page</span>
</Button>
