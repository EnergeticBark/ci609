<script>
    import LeafletMap from "$lib/components/LeafletMap.svelte";

    let { data } = $props();

    let statusMessage = $derived.by(() => {
        switch(data.deathType) {
            case "fence":
                return "Caught on fence";
            case "fenceElectrocuted":
                return "Electrocuted on fence";
            case "road":
                return "Killed on road";
            default:
                return "Alive";
        }
    });
</script>

<main>
    <article id="fullscreen-image">
        <h2>Sighting Details</h2>
        {#await data.sighting}
            <label>Loading sighting details...<progress></progress></label>
        {:then sighting}
            <picture><img src={sighting.image} alt=""></picture>
            <h3>Status</h3>
            <p>{statusMessage}</p>
            <h3>Time of sighting</h3>
            <time datetime={sighting.time}>{sighting.time}</time>
            <h3>Location</h3>
            <p>Coordinates: {sighting.latitude}&deg; N, {sighting.longitude}&deg; W</p>
            <p>Accuracy: {sighting.accuracy} meters</p>
            <LeafletMap latitude={sighting.latitude} longitude={sighting.longitude} accuracy={sighting.accuracy} />
            {#if sighting.notes}
                <h3>Notes</h3>
                <p class="description">{sighting.notes}</p>
            {/if}
        {/await}
    </article>
</main>