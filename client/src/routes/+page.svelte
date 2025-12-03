<script>
    let { data } = $props();
</script>

<h1>Welcome to SvelteKit</h1>
<p>Visit <a href="https://svelte.dev/docs/kit">svelte.dev/docs/kit</a> to read the documentation</p>
<ul>
    {#await data.sightings}
        <h1>Now Loading.....</h1>
    {:then sightings}
        {#each sightings as { id }}
            <li>{id}</li>
        {/each}
    {/await}
</ul>