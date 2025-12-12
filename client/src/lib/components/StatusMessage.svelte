<script>
    let { deathType } = $props();

    let statusMessage = $derived.by(() => {
        switch (deathType) {
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
</script>

{statusMessage}
