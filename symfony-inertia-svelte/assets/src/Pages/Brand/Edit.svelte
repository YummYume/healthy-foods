<script>
    import { Inertia } from "@inertiajs/inertia";
    import { useForm } from "@inertiajs/inertia-svelte";
    import { Button, GradientHeading } from "@brainandbones/skeleton";
    import Icon from "svelte-icons-pack/Icon.svelte";
    import TiArrowBack from "svelte-icons-pack/ti/TiArrowBack";

    import Form from "@app/Components/Brand/Form.svelte";
    import { title } from "@stores/seo";

    export let brand;

    title.set(`Edit "${brand.name}" brand`);

    let form = useForm(brand);

    function submit() {
        $form
            .transform(({ id, foods, ...data }) => ({ ...data }))
            .post(`/brand/edit/${brand.id}`, {
                preserveScroll: true,
                preserveState: true,
                only: ["brand", "errors", "flashMessages"]
            });
    }
</script>

<GradientHeading class="w-full text-center mb-10 text-4xl" tag="h2" direction="bg-gradient-to-l" from="from-primary-600" to="to-accent-600">
    Edit a brand
</GradientHeading>

<Form on:submit={submit} {form} />

<Button
    size="base"
    background="bg-primary-600"
    color="text-surface-200"
    ring="ring-transparent"
    weight="ring-none"
    rounded="rounded-full"
    width="w-auto"
    on:click={() => Inertia.visit(`/brand`)}
>
    <span slot="lead" class="fill-surface-200"><Icon src={TiArrowBack} /></span>
    <span>Back to brand list</span>
</Button>
