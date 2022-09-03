<script>
    import { Link } from "@inertiajs/inertia-svelte";

    export let entities = [];
    export let id = "id";
    export let name = "name";
    export let routePrefix = "/";
    export let emptyMessage = "No result";
</script>

{#if Array.isArray(entities)}
    {#each entities as entity, index}
        {@const lastIndex = entities.length - 1 !== index}
        <span>
            <Link
                class="text-primary-400 hover:underline hover:text-primary-600 dark:hover:text-primary-500 transition-all"
                href={`${routePrefix}${entity[id] ?? ""}`}>{entity[name] ?? ""}</Link
            >{lastIndex ? "," : ""}
        </span>
    {:else}
        <span>{emptyMessage}</span>
    {/each}
{:else}
    <span>{emptyMessage}</span>
{/if}
