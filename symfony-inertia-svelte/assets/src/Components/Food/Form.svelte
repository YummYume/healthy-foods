<script>
    import FormError from "@app/Components/Utils/FormError.svelte";

    export let form;
    export let categories = [];
    export let brands = [];

    let selectedCategories = $form.categories.map((c) => c.id);

    $: handleSelectedCategories(selectedCategories);

    function handleSelectedCategories(newCategories) {
        $form.categories = categories.filter((c) => newCategories.includes(c.id));
    }
</script>

<form on:submit|preventDefault class="my-10">
    <div class="form-group" class:invalid={$form.errors.name}>
        <label for="name">Name</label>
        <input name="name" class="form-input px-4 py-3 rounded-full" type="text" required bind:value={$form.name} />
        {#if $form.errors.name}
            <FormError errors={$form.errors.name} />
        {/if}
    </div>
    <div class="form-group" class:invalid={$form.errors.calories}>
        <label for="calories">Calories</label>
        <input name="calories" class="form-input px-4 py-3 rounded-full" type="number" min="0" bind:value={$form.calories} />
        {#if $form.errors.calories}
            <FormError errors={$form.errors.calories} />
        {/if}
    </div>
    <div class="form-group" class:invalid={$form.errors.categories}>
        <label for="categories">Categories</label>
        <select name="categories" class="form-input px-4 py-3 rounded-full" type="number" multiple bind:value={selectedCategories}>
            {#each categories as category}
                <option value={category.id}>{category.name}</option>
            {/each}
        </select>
        {#if $form.errors.categories}
            <FormError errors={$form.errors.categories} />
        {/if}
    </div>
    <div class="form-group" class:invalid={$form.errors.brands}>
        <label for="brands">Brand</label>
        <select
            name="brands"
            class="form-input px-4 py-3 rounded-full"
            type="number"
            value={$form.brand?.id}
            on:change={(e) => ($form.brand = brands.find((b) => b.id.toString() === e.target.value))}
        >
            <option value={null} />
            {#each brands as brand}
                <option value={brand.id}>{brand.name}</option>
            {/each}
        </select>
        {#if $form.errors.brands}
            <FormError errors={$form.errors.brands} />
        {/if}
    </div>
    {#if $form.errors.food}
        <div class="invalid my-2">
            <FormError errors={$form.errors.food} />
        </div>
    {/if}
    <button
        class="mt-2 bg-purple-600 px-5 py-3 text-sm shadow-sm font-medium tracking-wider  text-purple-100 rounded-full hover:shadow-2xl hover:bg-purple-700 disabled:opacity-50"
        type="submit"
        disabled={$form.processing}
    >
        Submit
    </button>
</form>
