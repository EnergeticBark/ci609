<script>
    let { href, image, deathType, time } = $props();

    let statusMessage = $derived.by(() => {
        switch(deathType) {
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

    let timestamp = $derived(new Date(time).toLocaleDateString());
</script>

<article>
    <a {href}>
        <div>
            <img src={image} alt="" loading="lazy">
        </div>
        <h3>{statusMessage}</h3>
        <p>Seen: {timestamp}</p>
    </a>
</article>

<style>
    article {
        background-color: var(--base);
        border: 1px solid var(--surface-1);
        border-radius: 0.5rem;
        text-align: center;
        text-wrap: balance;
        overflow: auto;
        overflow-wrap: break-word;
    }

    article:hover {
        background-color: var(--mantle);
    }

    a {
        display: contents;
        text-decoration: none;
        color: inherit;
    }

    article div {
        background-color: var(--mantle);
        align-content: center;
        border-bottom: 1px solid var(--surface-1);
    }

    article:hover div {
        background-color: var(--crust);
    }

    img {
        width: 100%;
        max-height: 12rem;
        height: auto;
        object-fit: contain;
        display: block;
    }

    h3 {
        margin: 0.25rem 0.5rem 0.5rem;
        align-content: center;
    }

    article:hover h3 {
        text-decoration: underline;
    }

    p {
        padding: 0.25rem 0.5625rem 0.25rem 0.5rem;
        margin: 0;
        border-top: 1px solid var(--surface-1);
        border-left: 1px solid var(--surface-1);
        border-top-left-radius: 0.5rem;
        background-color: var(--mantle);
    }

    article:hover p {
        background-color: var(--crust);
    }

    /* Grid layout */
    article {
        display: grid;
        /* Show the time on the bottom right corner of the card, taking up only as much width as it's content. */
        grid-template-columns: 1fr auto;
        /* The W3C validator will say this is invalid, this is an open issue:
           https://github.com/w3c/css-validator/issues/389
           https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_grid_layout/Subgrid#browser_compatibility
         */
        grid-template-rows: subgrid;
        grid-row: span 3;
        grid-template-areas:
        "image image"
        "title title"
        ".     time";
    }

    article div { grid-area: image; }
    article h3 { grid-area: title; }
    article p { grid-area: time; }
</style>