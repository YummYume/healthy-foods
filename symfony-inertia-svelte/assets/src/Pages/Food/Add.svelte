<script>
    import { Link } from "@inertiajs/inertia-svelte";
    import { useForm } from "@inertiajs/inertia-svelte";

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

<h2 class="w-full text-center mb-10 text-4xl">Add food</h2>

<Form on:submit={submit} {form} {categories} {brands} />

<Link
    class="bg-indigo-600 px-5 py-3 text-sm shadow-sm font-medium tracking-wider  text-indigo-100 rounded-full hover:shadow-2xl hover:bg-indigo-700"
    href="/food"
>
    Back to food list
</Link>
