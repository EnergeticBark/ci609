<script>
    import favicon from "$lib/assets/favicon.svg";

    import "modern-normalize";
    import "$lib/assets/global.css";
    import Navigation from "./Navigation.svelte";

    let { children } = $props();
</script>

<svelte:head>
    <link rel="manifest" href="manifest.json" />
    <link rel="icon" href={favicon} />
</svelte:head>

<header>
    <h1>Zap App Client</h1>
</header>
<Navigation />

<main>{@render children()}</main>

<footer>© Seth Humphries</footer>

<style>
    header {
        margin: 0 1rem;
    }

    main {
        margin: 0 1rem 1.5rem;
    }

    footer {
        text-align: center;
        padding: 1.5rem 0;
        background-color: var(--base);
    }

    @media (width >= 40rem) {
        header {
            margin: 0 3rem;
        }

        main {
            margin: 0 3rem 1.5rem;
        }
    }
</style>