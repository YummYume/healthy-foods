<script>
    import { Link } from "@inertiajs/inertia-svelte";
    import { Inertia } from "@inertiajs/inertia";

    import TableRowRelations from "../../Components/Utils/TableRowRelations.svelte";
    import { title } from "@stores/seo";

    export let brands;

    title.set(`Brand list`);

    function deleteBrand(brand) {
        const willDelete = confirm(`Are you sure you want to delete "${brand.name}"?`);

        if (willDelete) {
            Inertia.delete(`/brand/delete/${brand.id}`, {
                headers: { "X-CSRF-Token": brand.csrfToken },
                preserveState: true,
                preservescroll: true,
                only: ["brands", "flashMessages"]
            });
        }
    }
</script>

<h2 class="w-full text-center mb-10 text-4xl">Brand list</h2>

{#if brands.length}
    <span class="block my-10 text-2xl">{brands.length} {brands.length > 1 ? "brands" : "brand"} found.</span>

    <div class="overflow-auto shadow ring-1 ring-black ring-opacity-5 md:rounded-lg my-3">
        <table class="min-w-full divide-y divide-gray-300 table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Name</th>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Foods</th>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                {#each brands as brand}
                    <tr>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">{brand.name}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                            <TableRowRelations entities={brand.foods} routePrefix="/food/edit/" emptyMessage="No food" />
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                            <Link
                                class="bg-blue-600 px-5 py-3 mr-1 text-sm shadow-sm font-medium tracking-wider  text-blue-100 rounded-full hover:shadow-2xl hover:bg-blue-700"
                                href={`/brand/edit/${brand.id}`}
                            >
                                Edit
                            </Link>
                            <button
                                on:click={() => deleteBrand(brand)}
                                class="bg-red-500 px-5 py-3 ml-1 text-sm shadow-sm font-medium tracking-wider  text-red-100 rounded-full hover:shadow-2xl hover:bg-red-600"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                {/each}
            </tbody>
        </table>
    </div>
{:else}
    <span class="block my-10 text-2xl">No brand found.</span>
{/if}

<Link
    class="w-auto inline-block bg-green-600 px-5 py-3 my-3 text-sm shadow-sm font-medium tracking-wider  text-green-100 rounded-full hover:shadow-2xl hover:bg-green-700"
    href="/brand/add"
>
    Add a brand
</Link>
