<script>
    import StatusMessage from "$lib/components/StatusMessage.svelte";
    import LeafletMap from "./LeafletMap.svelte";

    let { image, deathType, time, latitude, longitude, accuracy, notes } =
        $props();

    const dateObject = $derived(new Date(time));

    let datetime = $derived(dateObject.toISOString());
    let timestamp = $derived(dateObject.toLocaleString());
</script>

<picture><img src={image} alt="a pangolin" /></picture>
<h3>Status</h3>
<p><StatusMessage {deathType} /></p>
<h3>Time of sighting</h3>
<time {datetime}>{timestamp}</time>
<h3>Location</h3>
<p>Coordinates: {latitude}&deg; N, {longitude}&deg; W</p>
<p>Accuracy: {accuracy} meters</p>
<LeafletMap {latitude} {longitude} {accuracy} />
{#if notes}
    <h3>Notes</h3>
    <p class="description">{notes}</p>
{/if}

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
        white-space: pre-line;
    }
</style>
