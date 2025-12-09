<script>
    const { children, ...props } = $props();
</script>

<button {...props}>
    {@render children()}
</button>

<style>
    button {
        border-radius: 0.5rem;
        border: 1px solid var(--surface-1);
        background-color: var(--blue);
        color: whitesmoke;
        padding: 0.5rem;
    }

    button:hover {
        cursor: pointer;
        background-color: var(--blue-hover);
        color: #fff;
    }
</style>