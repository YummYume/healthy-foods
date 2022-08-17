<script>
    import { Link } from "@inertiajs/inertia-svelte";
    import { Inertia } from "@inertiajs/inertia";

    import { title } from "@stores/seo";
    import TableRowRelations from "../../Components/Utils/TableRowRelations.svelte";

    export let foods;

    title.set(`Food list`);

    function deleteFood(food) {
        const willDelete = confirm(`Are you sure you want to delete "${food.name}"?`);

        if (willDelete) {
            Inertia.delete(`/food/delete/${food.id}`, {
                headers: { "X-CSRF-Token": food.csrfToken },
                preserveState: true,
                preservescroll: true,
                only: ["foods", "flashMessages"]
            });
        }
    }
</script>

<h2 class="w-full text-center mb-10 text-4xl">Food list</h2>

{#if foods.length}
    <span class="block my-10 text-2xl">{foods.length} {foods.length > 1 ? "foods" : "food"} found.</span>

    <div class="overflow-auto shadow ring-1 ring-black ring-opacity-5 md:rounded-lg my-3">
        <table class="min-w-full divide-y divide-gray-300 table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Name</th>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Calories</th>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Brand</th>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Categories</th>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                {#each foods as food}
                    <tr>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">{food.name}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">{food.calories ?? "Unknown"}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                            <TableRowRelations
                                entities={food.brand ? [food.brand] : []}
                                routePrefix="/brand/edit/"
                                emptyMessage="No brand"
                            />
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                            <TableRowRelations entities={food.categories} routePrefix="/category/edit/" emptyMessage="No categories" />
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                            <Link
                                class="bg-blue-600 px-5 py-3 mr-1 text-sm shadow-sm font-medium tracking-wider  text-blue-100 rounded-full hover:shadow-2xl hover:bg-blue-700"
                                href={`/food/edit/${food.id}`}
                            >
                                Edit
                            </Link>
                            <button
                                on:click={() => deleteFood(food)}
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
    <span class="block my-10 text-2xl">No food found.</span>
{/if}

<Link
    class="w-auto inline-block bg-green-600 px-5 py-3 my-3 text-sm shadow-sm font-medium tracking-wider  text-green-100 rounded-full hover:shadow-2xl hover:bg-green-700"
    href="/food/add"
>
    Add food
</Link>
