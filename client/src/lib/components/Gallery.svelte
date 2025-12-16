<script>
    const { children } = $props();
</script>

<div>
    {@render children()}
</div>

<style>
    /* Gallery grid layout */
    div {
        margin-top: 1rem;
        display: grid;
        grid-gap: 0.5rem;
        grid-template-columns: 1fr 1fr;
    }

    /* Media breakpoints:
       70rem: Desktop
       50rem: Tablet
       40rem: Mobile phone
     */
    @media (width >= 40rem) {
        div {
            grid-gap: 1rem;
        }
    }

    @media (width >= 50rem) {
        div {
            grid-template-columns: 1fr 1fr 1fr;
        }
    }

    @media (width >= 70rem) {
        div {
            grid-template-columns: 1fr 1fr 1fr 1fr;
        }
    }
</style>
