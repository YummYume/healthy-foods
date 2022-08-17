<script>
    import FormError from "@app/Components/Utils/FormError.svelte";

    export let form;
</script>

<form on:submit|preventDefault class="my-10">
    <div class="form-group" class:invalid={$form.errors.name}>
        <label for="name">Name</label>
        <input name="name" class="form-input px-4 py-3 rounded-full" type="text" required bind:value={$form.name} />
        {#if $form.errors.name}
            <FormError errors={$form.errors.name} />
        {/if}
    </div>
    {#if $form.errors.brand}
        <div class="invalid my-2">
            <FormError errors={$form.errors.brand} />
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
