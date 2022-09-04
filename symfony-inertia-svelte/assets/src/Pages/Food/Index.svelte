<script>
    import { Inertia } from "@inertiajs/inertia";
    import { Button, GradientHeading } from "@brainandbones/skeleton";
    import Icon from "svelte-icons-pack/Icon.svelte";
    import AiOutlinePlus from "svelte-icons-pack/ai/AiOutlinePlus";
    import AiFillDelete from "svelte-icons-pack/ai/AiFillDelete";
    import AiFillEdit from "svelte-icons-pack/ai/AiFillEdit";
    import { Datatable } from "svelte-simple-datatables";
    import { dialogStore } from "@brainandbones/skeleton";
    import { _, locale } from "svelte-i18n";

    import { title } from "@stores/seo";
    import TableRowRelations from "../../Components/Utils/TableRowRelations.svelte";

    export let foods;

    let rows;
    let settings = { sortable: true, pagination: true, scrollY: true, rowsPerPage: 50, columnFilter: true, css: false };

    $: $locale, (settings = setDatatableSettings());
    $: $_("food.list"), title.set($_("food.list"));

    function setDatatableSettings() {
        return {
            sortable: true,
            pagination: true,
            scrollY: true,
            rowsPerPage: 50,
            columnFilter: true,
            css: false,
            labels: {
                search: $_("common.search"),
                filter: $_("common.filter"),
                noRows: $_("datatable.no_row"),
                info: $_("datatable.info"),
                previous: $_("common.previous"),
                next: $_("common.next")
            }
        };
    }

    function deleteFood(food) {
        dialogStore.trigger({
            type: "confirm",
            title: $_("dialog.delete.confirm"),
            body: $_("dialog.delete.are_you_sure", { values: { name: food.name } }),
            result: (willDelete) => {
                if (willDelete) {
                    Inertia.delete(`/food/delete/${food.id}`, {
                        headers: { "X-CSRF-Token": food.csrfToken },
                        preserveState: true,
                        preservescroll: true,
                        only: ["foods", "flashMessages"]
                    });
                }
            }
        });
    }
</script>

<GradientHeading
    class="w-full text-center mb-10 text-4xl"
    tag="h2"
    direction="bg-gradient-to-b"
    from="from-warning-300"
    to="to-primary-600"
>
    {$_("food.list")}
</GradientHeading>

<div class="datatable-container">
    {#key $locale}
        <Datatable {settings} data={foods} bind:dataRows={rows}>
            <thead>
                <th data-key="name">{$_("food.name")}</th>
                <th data-key="calories">{$_("food.calories")}</th>
                <th data-key={`(row) => row.brand.name ?? '${$_("food.no_brand")}'`}>{$_("food.brand")}</th>
                <th data-key={`(row) => Array.isArray(row?.foods) ? row.foods.map(f => f.name).join(' ') : '${$_("food.no_category")}'`}>
                    {$_("food.categories")}
                </th>
                <th>{$_("common.actions")}</th>
            </thead>
            <tbody>
                {#if rows}
                    {#each $rows as row}
                        <tr>
                            <td class="whitespace-nowrap px-3 py-4 text-sm">{row.name}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm">{row.calories}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                <TableRowRelations
                                    entities={() => (row.brand ? [row.brand] : [])}
                                    routePrefix="/brand/edit/"
                                    emptyMessage={$_("food.no_brand")}
                                />
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                <TableRowRelations
                                    entities={row.categories}
                                    routePrefix="/category/edit/"
                                    emptyMessage={$_("food.no_category")}
                                />
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
                                    class="my-1 ml-1"
                                    on:click={() => Inertia.visit(`/food/edit/${row.id}`)}
                                >
                                    <span slot="lead" class="fill-surface-200"><Icon src={AiFillEdit} /></span>
                                    <span>{$_("common.edit")}</span>
                                </Button>
                                <Button
                                    size="sm"
                                    background="bg-warning-600"
                                    color="text-surface-200"
                                    ring="ring-transparent"
                                    weight="ring-none"
                                    rounded="rounded-lg"
                                    width="w-auto"
                                    class="my-1 ml-1"
                                    on:click={() => deleteFood(row)}
                                >
                                    <span slot="lead" class="fill-surface-200"><Icon src={AiFillDelete} /></span>
                                    <span>{$_("common.delete")}</span>
                                </Button>
                            </td>
                        </tr>
                    {/each}
                {/if}
            </tbody>
        </Datatable>
    {/key}
</div>

<Button
    size="base"
    background="bg-primary-800"
    color="text-surface-200"
    ring="ring-transparent"
    weight="ring-none"
    rounded="rounded-full"
    width="w-auto"
    on:click={() => Inertia.visit("/food/add")}
>
    <span slot="lead" class="fill-surface-200"><Icon src={AiOutlinePlus} /></span>
    <span>{$_("food.add")}</span>
</Button>
