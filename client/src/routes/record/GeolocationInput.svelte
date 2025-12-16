<script>
    import BlueButton from "$lib/components/BlueButton.svelte";

    let { ...props } = $props();

    let position = $state.raw();
    let positionError = $state();

    const tryEnablingGeolocation = () => {
        navigator.geolocation.getCurrentPosition(
            (currentPosition) => (position = currentPosition),
            (error) => (positionError = error),
            { enableHighAccuracy: true },
        );
    };

    navigator.permissions
        .query({ name: "geolocation" })
        .then((permissionStatus) => {
            if (permissionStatus.state === "granted") {
                tryEnablingGeolocation();
            } else if (permissionStatus.state === "prompt") {
                // Do nothing, wait for the user to enable geolocation using the button.
            } else if (permissionStatus.state === "denied") {
                positionError = new Error(
                    "You didn't grant location permissions.",
                );
            }
        });

    const inputLifecycle = (node) => {
        // The "Enable location" button will exist as long as location permissions are disabled. So as long as the
        // button is still on the form, it's invalid.
        node.setCustomValidity("Enable location permissions");
    };
</script>

{#if !position}
    <BlueButton
        type="button"
        onclick={tryEnablingGeolocation}
        {@attach inputLifecycle}
        {...props}>Enable location</BlueButton
    >
{:else}
    <input type="hidden" name="latitude" value={position.coords.latitude} />
    <input type="hidden" name="longitude" value={position.coords.longitude} />
    <input type="hidden" name="accuracy" value={position.coords.accuracy} />
    <input type="hidden" name="time" value={position.timestamp} />
    <p class="attached">Your current location is attached.</p>
{/if}
{#if positionError}
    <p>{positionError.message}</p>
{/if}

<style>
    .attached:before {
        content: "✓";
        padding-right: 0.3rem;
        color: var(--green);
    }

    .attached {
        margin: 0;
        overflow: auto;
        overflow-wrap: anywhere;
        background-color: var(--base);
        padding: 1rem;
        border-radius: 0.5rem;
    }
</style>
