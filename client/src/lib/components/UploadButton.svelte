<script>
    let props = $props()
</script>

<a {...props}>Upload new sightings</a>

<style>
    a {
        text-decoration: none;
        border-radius: 0.5rem;
        border: 1px solid var(--surface-1);
        background-color: var(--blue);
        color: whitesmoke;
        padding: 0.5rem;
    }

    a:hover {
        background-color: var(--blue-hover);
        color: #fff;
    }
</style>