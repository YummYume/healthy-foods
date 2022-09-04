<script>
    import { Button } from "@brainandbones/skeleton";
    import Icon from "svelte-icons-pack/Icon.svelte";
    import AiOutlineCheck from "svelte-icons-pack/ai/AiOutlineCheck";
    import Select from "svelte-select";
    import { _ } from "svelte-i18n";

    import FormError from "@app/Components/Utils/FormError.svelte";

    export let form;
    export let categories = [];
    export let brands = [];
</script>

<form on:submit|preventDefault class="my-10">
    <div class="form-group" class:invalid={$form.errors.name}>
        <label for="name">{$_("food.name")}</label>
        <input id="name" name="name" class="form-input" type="text" required bind:value={$form.name} />
        {#if $form.errors.name}
            <FormError errors={$form.errors.name} />
        {/if}
    </div>
    <div class="form-group" class:invalid={$form.errors.calories}>
        <label for="calories">{$_("food.calories")}</label>
        <input id="calories" name="calories" class="form-input" type="number" min="0" bind:value={$form.calories} />
        {#if $form.errors.calories}
            <FormError errors={$form.errors.calories} />
        {/if}
    </div>
    <div class="form-group" class:invalid={$form.errors.categories}>
        <label for="categories">{$_("food.categories")}</label>
        <Select
            id="categories"
            hasError={$form.errors.categories}
            items={categories}
            value={$form.categories?.length > 0 ? $form.categories : null}
            isMulti
            labelIdentifier="name"
            optionIdentifier="id"
            inputStyles="box-shadow: none; height: 32px; border-radius: 0px;"
            containerStyles="min-height: 50px;"
            placeholder={$_("common.none")}
            on:select={(e) => ($form.categories = e.detail ?? [])}
            on:clear={(e) => ($form.categories = e.detail ?? [])}
        />
        {#if $form.errors.categories}
            <FormError errors={$form.errors.categories} />
        {/if}
    </div>
    <div class="form-group" class:invalid={$form.errors.brand}>
        <label for="brand">{$_("food.brand")}</label>
        <Select
            id="brand"
            hasError={$form.errors.brand}
            items={brands}
            value={$form.brand}
            labelIdentifier="name"
            optionIdentifier="id"
            placeholder={$_("common.none")}
            on:select={(e) => ($form.brand = e.detail)}
            on:clear={() => ($form.brand = null)}
        />
        {#if $form.errors.brand}
            <FormError errors={$form.errors.brand} />
        {/if}
    </div>
    {#if $form.errors.food}
        <div class="invalid my-2">
            <FormError errors={$form.errors.food} />
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
