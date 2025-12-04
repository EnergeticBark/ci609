<script>
    import favicon from '$lib/assets/favicon.svg';

    import 'modern-normalize';
    import '$lib/assets/shared.css';

	let { children } = $props();
</script>

<svelte:head>
	<link rel="icon" href={favicon} />
</svelte:head>

{@render children()}
