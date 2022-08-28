<script>
    import { Inertia } from "@inertiajs/inertia";
    import { Button, GradientHeading } from "@brainandbones/skeleton";
    import Icon from "svelte-icons-pack/Icon.svelte";
    import AiOutlinePlus from "svelte-icons-pack/ai/AiOutlinePlus";
    import AiFillDelete from "svelte-icons-pack/ai/AiFillDelete";
    import AiFillEdit from "svelte-icons-pack/ai/AiFillEdit";
    import { Datatable } from "svelte-simple-datatables";

    import TableRowRelations from "../../Components/Utils/TableRowRelations.svelte";
    import { title } from "@stores/seo";

    export let categories;

    const settings = { sortable: true, pagination: true, scrollY: true, rowsPerPage: 50, columnFilter: true, css: false };

    let rows;

    title.set(`Category list`);

    function deleteCategory(category) {
        const willDelete = confirm(`Are you sure you want to delete "${category.name}"?`);

        if (willDelete) {
            Inertia.delete(`/category/delete/${category.id}`, {
                headers: { "X-CSRF-Token": category.csrfToken },
                preserveState: true,
                preservescroll: true,
                only: ["categories", "flashMessages"]
            });
        }
    }
</script>

<GradientHeading
    class="w-full text-center mb-10 text-4xl"
    tag="h2"
    direction="bg-gradient-to-b"
    from="from-warning-300"
    to="to-primary-600"
>
    Category list
</GradientHeading>

<div class="datatable-container">
    <Datatable {settings} data={categories} bind:dataRows={rows}>
        <thead>
            <th data-key="name">Name</th>
            <th data-key="(row) => Array.isArray(row?.foods) ? row.foods.map(f => f.name).join(' ') : 'No food'">Foods</th>
            <th>Actions</th>
        </thead>
        <tbody>
            {#if rows}
                {#each $rows as row}
                    <tr>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">{row.name}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                            <TableRowRelations entities={row.foods} routePrefix="/food/edit/" emptyMessage="No food" />
                        </td>
                        <td>
                            <Button
                                size="sm"
                                background="bg-accent-600"
                                color="text-surface-200"
                                ring="ring-transparent"
                                weight="ring-none"
                                rounded="rounded-lg"
                                width="w-auto"
                                on:click={() => Inertia.visit(`/category/edit/${row.id}`)}
                            >
                                <span slot="lead" class="fill-surface-200"><Icon src={AiFillEdit} /></span>
                                <span>Edit</span>
                            </Button>
                            <Button
                                size="sm"
                                background="bg-warning-600"
                                color="text-surface-200"
                                ring="ring-transparent"
                                weight="ring-none"
                                rounded="rounded-lg"
                                width="w-auto"
                                on:click={() => deleteCategory(row)}
                            >
                                <span slot="lead" class="fill-surface-200"><Icon src={AiFillDelete} /></span>
                                <span>Delete</span>
                            </Button>
                        </td>
                    </tr>
                {/each}
            {/if}
        </tbody>
    </Datatable>
</div>

<Button
    size="base"
    background="bg-primary-800"
    color="text-surface-200"
    ring="ring-transparent"
    weight="ring-none"
    rounded="rounded-full"
    width="w-auto"
    on:click={() => Inertia.visit("/category/add")}
>
    <span slot="lead" class="fill-surface-200"><Icon src={AiOutlinePlus} /></span>
    <span>Add a category</span>
</Button>
