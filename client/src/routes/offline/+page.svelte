<script>
    import SightingPreview from "$lib/components/SightingPreview.svelte";
    import BlueButton from "$lib/components/BlueButton.svelte";

    let { data } = $props();
</script>

<main>
    <h2>Your offline pangolin sightings</h2>
    <BlueButton type="button">Upload All</BlueButton>
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