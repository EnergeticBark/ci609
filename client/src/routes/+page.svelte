<script>
    import SightingPreview from "$lib/components/SightingPreview.svelte";
    import Gallery from "$lib/components/Gallery.svelte";
    import LoadingIndicator from "$lib/components/LoadingIndicator.svelte";

    let { data } = $props();
</script>

<h2>Pangolin sightings</h2>
{#await data.sightings}
    <LoadingIndicator>Loading pangolin sightings...</LoadingIndicator>
{:then sightings}
    <Gallery>
        {#each sightings as sighting}
            <SightingPreview
                href="/ci609/sighting/{sighting.id}"
                {...sighting}
            />
        {/each}
    </Gallery>
{:catch error}
    <p>{error.message}</p>
{/await}
