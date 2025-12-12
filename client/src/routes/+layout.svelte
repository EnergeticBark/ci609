<script>
    import favicon from "$lib/assets/favicon.svg";

    import "modern-normalize";
    import "$lib/assets/shared.css";
    import Navigation from "$lib/components/Navigation.svelte";

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

{@render children()}

<footer>© Seth Humphries</footer>
