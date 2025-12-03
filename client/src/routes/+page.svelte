<script>
    let { data } = $props();
</script>

<h1>Welcome to SvelteKit</h1>
<p>Visit <a href="https://svelte.dev/docs/kit">svelte.dev/docs/kit</a> to read the documentation</p>
{#await data.sightings}
    <h1>Now Loading.....</h1>
{:then sightings}
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