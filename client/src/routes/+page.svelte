<script>
    import SightingPreview from "$lib/components/SightingPreview.svelte";
    import UploadButton from "$lib/components/UploadButton.svelte";

    let { data } = $props();
</script>

<header>
    <h1>Zap App Client</h1>
</header>

<main>
    <UploadButton href="/ci609/upload">Upload new sightings</UploadButton>
    {#await data.sightings}
        <label>Loading pangolin sightings...<progress></progress></label>
    {:then sightings}
        <h2>Pangolin sightings</h2>
        <div id="gallery">
            {#each sightings as sighting}
                <SightingPreview {...sighting} />
            {/each}
        </div>
    {/await}
</main>

<footer>© Seth Humphries</footer>