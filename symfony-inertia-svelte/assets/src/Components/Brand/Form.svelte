<script>
    import { Button } from "@brainandbones/skeleton";
    import Icon from "svelte-icons-pack/Icon.svelte";
    import AiOutlineCheck from "svelte-icons-pack/ai/AiOutlineCheck";
    import { _ } from "svelte-i18n";

    import FormError from "@app/Components/Utils/FormError.svelte";

    export let form;
</script>

<form on:submit|preventDefault class="my-10">
    <div class="form-group" class:invalid={$form.errors.name}>
        <label for="name">{$_("brand.name")}</label>
        <input id="name" name="name" class="form-input" type="text" required bind:value={$form.name} />
        {#if $form.errors.name}
            <FormError errors={$form.errors.name} />
        {/if}
    </div>
    {#if $form.errors.brand}
        <div class="invalid my-2">
            <FormError errors={$form.errors.brand} />
        </div>
    {/if}
    <Button
        size="base"
        background="bg-accent-800"
        color="text-surface-200"
        ring="ring-transparent"
        weight="ring-none"
        rounded="rounded-full"
        width="w-auto"
        type="submit"
        class="mt-2"
        disabled={$form.processing}
    >
        <span slot="lead" class="fill-surface-200"><Icon src={AiOutlineCheck} /></span>
        <span>{$_("common.save")}</span>
    </Button>
</form>
