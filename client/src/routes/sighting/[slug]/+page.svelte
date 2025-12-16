<script>
    import SightingDetails from "./SightingDetails.svelte";
    import LoadingIndicator from "$lib/components/LoadingIndicator.svelte";

    let { data } = $props();
</script>

<main>
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
</main>

<style>
    main {
        padding: 0 1rem 1.5rem;
    }

    @media (width >= 40rem) {
        main {
            padding: 0 3rem 1.5rem;
        }
    }
</style>