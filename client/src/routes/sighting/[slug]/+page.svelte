<script>
    import LeafletMap from "$lib/components/LeafletMap.svelte";
    import StatusMessage from "$lib/components/StatusMessage.svelte";

    let { data } = $props();
</script>

<main>
    <article>
        <h2>Sighting Details</h2>
        {#await data.sighting}
            <label>Loading sighting details...<progress></progress></label>
        {:then sighting}
            <picture><img src={sighting.image} alt="" /></picture>
            <h3>Status</h3>
            <p><StatusMessage deathType={sighting.deathType} /></p>
            <h3>Time of sighting</h3>
            <time datetime={sighting.time}>{sighting.time}</time>
            <h3>Location</h3>
            <p>
                Coordinates: {sighting.latitude}&deg; N, {sighting.longitude}&deg;
                W
            </p>
            <p>Accuracy: {sighting.accuracy} meters</p>
            <LeafletMap
                latitude={sighting.latitude}
                longitude={sighting.longitude}
                accuracy={sighting.accuracy}
            />
            {#if sighting.notes}
                <h3>Notes</h3>
                <p class="description">{sighting.notes}</p>
            {/if}
        {:catch error}
            <p>{error.message}</p>
        {/await}
    </article>
</main>

<style>
    img {
        background-color: var(--base);
        max-height: 50vh;
        width: 100%;
        height: auto;
        object-fit: contain;
    }

    .description {
        overflow: auto;
        overflow-wrap: anywhere;
        background-color: var(--base);
        padding: 1rem;
        border-radius: 0.5rem;
    }
</style>
