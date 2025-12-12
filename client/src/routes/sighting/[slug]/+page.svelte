<script>
    import SightingDetails from "./SightingDetails.svelte";

    let { data } = $props();
</script>

<main>
    <article>
        <h2>Sighting Details</h2>
        {#await data.sighting}
            <label>Loading sighting details...<progress></progress></label>
        {:then sighting}
            <SightingDetails {...sighting} />
        {:catch error}
            <p>{error.message}</p>
        {/await}
    </article>
</main>
