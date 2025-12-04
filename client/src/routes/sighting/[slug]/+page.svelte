<script>
    let { data } = $props();
</script>

<main>
    <article id="fullscreen-image">
        <h2>Sighting Details</h2>
        {#await data.sighting}
            <label>Loading sighting details...<progress></progress></label>
        {:then sighting}
            <picture><img src={sighting.image} alt=""></picture>
            <h3>Time of sighting</h3>
            <time datetime={sighting.time}>{sighting.time}</time>
            {#if sighting.notes}
                <h3>Notes</h3>
                <p class="description">{sighting.notes}</p>
            {/if}
        {/await}
    </article>
</main>