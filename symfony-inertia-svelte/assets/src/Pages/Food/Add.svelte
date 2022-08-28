<script>
    import { Inertia } from "@inertiajs/inertia";
    import { useForm } from "@inertiajs/inertia-svelte";
    import { Button, GradientHeading } from "@brainandbones/skeleton";
    import Icon from "svelte-icons-pack/Icon.svelte";
    import TiArrowBack from "svelte-icons-pack/ti/TiArrowBack";

    import Form from "@app/Components/Food/Form.svelte";
    import { title } from "@stores/seo";

    export let food;
    export let categories;
    export let brands;

    title.set("Add a new food");

    let form = useForm(food);

    function submit() {
        $form
            .transform(({ id, ...data }) => ({
                ...data,
                categories: data.categories ? data.categories.map((c) => c?.id) : [],
                brand: data.brand?.id
            }))
            .post("/food/add", {
                preserveScroll: true,
                preserveState: false,
                only: ["food", "errors", "flashMessages"]
            });
    }
</script>

<GradientHeading class="w-full text-center mb-10 text-4xl" tag="h2" direction="bg-gradient-to-r" from="from-primary-600" to="to-accent-600">
    Add a food
</GradientHeading>

<Form on:submit={submit} {form} {categories} {brands} />

<Button
    size="base"
    background="bg-primary-600"
    color="text-surface-200"
    ring="ring-transparent"
    weight="ring-none"
    rounded="rounded-full"
    width="w-auto"
    on:click={() => Inertia.visit(`/food`)}
>
    <span slot="lead" class="fill-surface-200"><Icon src={TiArrowBack} /></span>
    <span>Back to food list</span>
</Button>
