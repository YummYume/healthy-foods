<script>
    import { Inertia } from "@inertiajs/inertia";
    import { useForm } from "@inertiajs/inertia-svelte";
    import { Button, GradientHeading } from "@brainandbones/skeleton";
    import Icon from "svelte-icons-pack/Icon.svelte";
    import TiArrowBack from "svelte-icons-pack/ti/TiArrowBack";

    import Form from "@app/Components/Category/Form.svelte";
    import { title } from "@stores/seo";

    export let category;

    title.set("Add a new category");

    let form = useForm(category);

    function submit() {
        $form
            .transform(({ id, foods, ...data }) => ({ ...data }))
            .post("/category/add", {
                preserveScroll: true,
                preserveState: false,
                only: ["category", "errors", "flashMessages"]
            });
    }
</script>

<GradientHeading class="w-full text-center mb-10 text-4xl" tag="h2" direction="bg-gradient-to-r" from="from-primary-600" to="to-accent-600">
    Add a category
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
    on:click={() => Inertia.visit(`/category`)}
>
    <span slot="lead" class="fill-surface-200"><Icon src={TiArrowBack} /></span>
    <span>Back to category list</span>
</Button>
