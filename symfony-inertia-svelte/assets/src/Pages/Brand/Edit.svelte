<script>
    import { Link } from "@inertiajs/inertia-svelte";
    import { useForm } from "@inertiajs/inertia-svelte";

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

<h2 class="w-full text-center mb-10 text-4xl">Edit brand</h2>

<Form on:submit={submit} {form} />

<Link
    class="bg-indigo-600 px-5 py-3 text-sm shadow-sm font-medium tracking-wider  text-indigo-100 rounded-full hover:shadow-2xl hover:bg-indigo-700"
    href="/brand"
>
    Back to brand list
</Link>
