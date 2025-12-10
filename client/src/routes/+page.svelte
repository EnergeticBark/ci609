<script>
    import SightingPreview from "$lib/components/SightingPreview.svelte";

    let { data } = $props();
</script>

<main>
    <h2>Pangolin sightings</h2>
    {#await data.sightings}
        <label>Loading pangolin sightings...<progress></progress></label>
    {:then sightings}
        <div id="gallery">
            {#each sightings as sighting}
                <SightingPreview href="/ci609/sighting/{sighting.id}" {...sighting} />
            {/each}
        </div>
    {:catch error}
        <p>{error.message}</p>
    {/await}
</main>