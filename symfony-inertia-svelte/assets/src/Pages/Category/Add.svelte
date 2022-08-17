<script>
    import { Link } from "@inertiajs/inertia-svelte";
    import { useForm } from "@inertiajs/inertia-svelte";

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

<h2 class="w-full text-center mb-10 text-4xl">Add a category</h2>

<Form on:submit={submit} {form} />

<Link
    class="bg-indigo-600 px-5 py-3 text-sm shadow-sm font-medium tracking-wider  text-indigo-100 rounded-full hover:shadow-2xl hover:bg-indigo-700"
    href="/category"
>
    Back to category list
</Link>
