<script>
    export let errors;
</script>

{#if Array.isArray(errors)}
    {#each errors as error}
        <div class="form-error block">{error}</div>
    {/each}
{:else}
    <div class="form-error block">{errors}</div>
{/if}
