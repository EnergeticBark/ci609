<script>
    import SightingDetails from "./SightingDetails.svelte";
    import LoadingIndicator from "$lib/components/LoadingIndicator.svelte";

    let { data } = $props();
</script>

<article>
    <h2>Sighting Details</h2>
    {#await data.sighting}
        <LoadingIndicator>Loading sighting details...</LoadingIndicator>
    {:then sighting}
        <SightingDetails {...sighting} />
    {:catch error}
        <p>{error.message}</p>
    {/await}
</article>
