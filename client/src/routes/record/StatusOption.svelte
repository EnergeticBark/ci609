<script>
    import StatusMessage from "$lib/components/StatusMessage.svelte";

    let { value } = $props();
</script>

<option {value}><StatusMessage deathType={value} /></option>
