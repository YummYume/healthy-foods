<script lang="ts">
    export let label: string = "";
    export let name: string;
    export let error: string = "";
    export let touched: boolean = true;
    export let choices: Array<string>;
    export let value: string = "";

    $: isError = error !== "" && touched;
</script>

<div class="input-group">
    <label class="input-label" for={name}>
        <slot name="label">
            {label}
        </slot>
    </label>
    <select {...$$restProps} class:invalid={isError} bind:value {name} id={name} on:change on:change>
        <slot name="select">
            <option value="" />
            {#each choices as choice}
                <option value={choice}>
                    {choice}
                </option>
            {/each}
        </slot>
    </select>
    <span class="input-error">
        <slot name="error" {isError}>
            {#if isError}
                {error}
            {/if}
        </slot>
    </span>
    <slot />
</div>

<style>
    @import "./inputs.css";
</style>
