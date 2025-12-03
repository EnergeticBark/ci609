<script>
    let { data } = $props();
</script>

<header>
    <h1>Zap App Client</h1>
</header>

<main>
    {#await data.sightings}
        <label>Loading pangolin sightings...<progress></progress></label>
    {:then sightings}
        <h2>Pangolin sightings</h2>
        <dl>
            {#each sightings as { id, image, deathType, time }}
                <dt>id</dt>
                <dd>{id}</dd>

                <dt>image</dt>
                <dd>{image}</dd>

                <dt>deathType</dt>
                <dd>{deathType}</dd>

                <dt>time</dt>
                <dd>{time}</dd>
            {/each}
        </dl>
    {/await}
</main>

<footer>© Seth Humphries</footer>