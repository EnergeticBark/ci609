<script>
    import favicon from '$lib/assets/favicon.svg';

    import 'modern-normalize';
    import '$lib/assets/shared.css';
    import UploadButton from "$lib/components/UploadButton.svelte";

	let { children } = $props();
</script>

<svelte:head>
    <link rel="manifest" href="manifest.json" />
	<link rel="icon" href={favicon} />
</svelte:head>

<header>
    <h1>Zap App Client</h1>
    <UploadButton href="/ci609/upload">Upload new sightings</UploadButton>
</header>

{@render children()}

<footer>© Seth Humphries</footer>