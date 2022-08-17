<script>
    import { Link } from "@inertiajs/inertia-svelte";
    import { useForm } from "@inertiajs/inertia-svelte";
    import { Confetti } from "svelte-confetti";
    import { toast } from "@zerodevx/svelte-toast";

    import Form from "@app/Components/Food/Form.svelte";
    import { title } from "@stores/seo";

    export let food;
    export let categories;
    export let brands;
    export let easterEgg = false;

    title.set(`Edit "${food.name}" food`);

    $: if (easterEgg) {
        toast.push("Weeee! Easter egg!");
    }

    let form = useForm(food);

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

<h2 class="w-full text-center mb-10 text-4xl">Edit food</h2>

<Form on:submit={submit} {form} {categories} {brands} />

<Link
    class="bg-indigo-600 px-5 py-3 text-sm shadow-sm font-medium tracking-wider  text-indigo-100 rounded-full hover:shadow-2xl hover:bg-indigo-700"
    href="/food"
>
    Back to food list
</Link>

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
