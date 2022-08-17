<script lang="ts">
    export let label: string = "";
    export let name: string;
    export let error: string = "";
    export let touched: boolean = true;
    export let type: "text" | "email" | "tel" | "password" = "text";
    export let value: string;

    $: isError = error !== "" && touched;

    function typeAction(node: HTMLInputElement): void {
        node.type = type;
    }
</script>

<div class="input-group">
    <label class="input-label" for={name}>
        <slot name="label">
            {label}
        </slot>
    </label>
    <input use:typeAction {...$$restProps} class:invalid={isError} bind:value {name} on:change on:input />
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
