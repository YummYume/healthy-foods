<script>
    import { GradientHeading } from "@brainandbones/skeleton";
    import { _ } from "svelte-i18n";

    import { title, description } from "@stores/seo";

    $: $_("menu.home"), title.set($_("menu.home"));
    $: $_("menu.description"), description.set($_("home.description"));
</script>

<GradientHeading
    class="w-full text-center mb-10 text-4xl"
    tag="h2"
    direction="bg-gradient-to-b"
    from="from-warning-300"
    to="to-primary-600"
>
    {$_("common.welcome_title", { values: { name: "Healthy Foods" } })}
</GradientHeading>

<div class="text-lg tracking-wide leading-7">
    <p class="my-3">{$_("home.presentation.p1")}</p>
    <p class="my-3">{$_("home.presentation.p2")}</p>
    <ol class="my-3">
        <li><strong class="font-bold">01.</strong> {$_("home.presentation.p3.li1")}</li>
        <li><strong class="font-bold">02.</strong> {$_("home.presentation.p3.li2")}</li>
        <li><strong class="font-bold">03.</strong> {$_("home.presentation.p3.li3")}</li>
        <li><strong class="font-bold">04.</strong> {$_("home.presentation.p3.li4")}</li>
    </ol>
    <p class="my-3">{$_("home.presentation.p4")}</p>
</div>

<p class="my-3 text-sm text-gray-600">
    {$_("home.presentation.generated_by")}
    <a
        class="text-primary-400 hover:underline hover:text-primary-600"
        href="https://app.inferkit.com/demo"
        alt="app.inferkit.com"
        target="_blank"
    >
        https://app.inferkit.com/demo
    </a> (lol).
</p>
