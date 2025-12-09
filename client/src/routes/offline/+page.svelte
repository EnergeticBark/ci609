<script>
    import SightingPreview from "$lib/components/SightingPreview.svelte";

    let { data } = $props();
</script>

<main>
    <h2>Your offline pangolin sightings</h2>
    {#await data.sightings}
        <label>Loading offline pangolin sightings...<progress></progress></label>
    {:then sightings}
        <div id="gallery">
            {#each sightings as sighting}
                <SightingPreview href="#" {...sighting} />
            {/each}
        </div>
    {/await}
</main>