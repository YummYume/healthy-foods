<script>
    import { Link } from "@inertiajs/inertia-svelte";
    import { useForm } from "@inertiajs/inertia-svelte";
    import { Confetti } from "svelte-confetti";
    import { toast } from "@zerodevx/svelte-toast";
    import { Inertia } from "@inertiajs/inertia";
    import { Button, GradientHeading } from "@brainandbones/skeleton";
    import Icon from "svelte-icons-pack/Icon.svelte";
    import TiArrowBack from "svelte-icons-pack/ti/TiArrowBack";
    import { _ } from "svelte-i18n";

    import Form from "@app/Components/Food/Form.svelte";
    import { title } from "@stores/seo";

    export let food;
    export let categories;
    export let brands;
    export let easterEgg = false;

    let form = useForm(food);

    $: $_("food.title"), title.set($_("food.title", { values: { name: food.name } }));
    $: if (easterEgg) {
        toast.push("Weeee! Easter egg!");
    }

    function submit() {
        $form
            .transform(({ id, ...data }) => ({
                ...data,
                categories: data.categories ? data.categories.map((c) => c?.id) : [],
                brand: data.brand?.id
            }))
            .post(`/food/edit/${food.id}`, {
                preserveScroll: true,
                preserveState: true,
                only: ["food", "errors", "flashMessages", "easterEgg"]
            });
    }
</script>

{#if easterEgg}
    <div class="easter-egg">
        <Confetti x={[-5, 5]} y={[0, 0.1]} delay={[250, 5000]} duration="2000" amount="500" fallDistance="100vh" />
    </div>
{/if}

<GradientHeading class="w-full text-center mb-10 text-4xl" tag="h2" direction="bg-gradient-to-l" from="from-primary-600" to="to-accent-600">
    {$_("food.edit")}
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
    <span>{$_("food.back_to_list")}</span>
</Button>

<style lang="scss">
    .easter-egg {
        position: fixed;
        top: -50px;
        left: 0;
        height: 100vh;
        width: 100vw;
        display: flex;
        justify-content: center;
        overflow: hidden;
        pointer-events: none;
    }
</style>
