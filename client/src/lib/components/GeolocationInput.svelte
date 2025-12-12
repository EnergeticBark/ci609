<script>
    import BlueButton from "$lib/components/BlueButton.svelte";

    let { ...props } = $props();

    let position = $state.raw();
    let positionError = $state();

    const tryEnablingGeolocation = () => {
        navigator.geolocation.getCurrentPosition(
            (currentPosition) => position = currentPosition,
            (error) => positionError = error,
            {enableHighAccuracy: true},
        );
    }

    navigator.permissions.query({ name: "geolocation" }).then((permissionStatus) => {
        if (permissionStatus.state === "granted") {
            tryEnablingGeolocation();
        } else if (permissionStatus.state === "prompt") {
            // Do nothing, wait for the user to enable geolocation using the button.
        } else if (permissionStatus.state === "denied") {
            positionError = new Error("You didn't grant location permissions.");
        }
    });

    const inputLifecycle = (node) => {
        // The "Enable location" button will exist as long as location permissions are disabled. So as long as the
        // button is still on the form, it's invalid.
        node.setCustomValidity("Enable location permissions");
    }
</script>

{#if !position}
    <BlueButton onclick={tryEnablingGeolocation} {@attach inputLifecycle} {...props}>Enable location</BlueButton>
{:else}
    <input
        type="hidden"
        name="latitude"
        value={position.coords.latitude}
    />
    <input
        type="hidden"
        name="longitude"
        value={position.coords.longitude}
    />
    <input
        type="hidden"
        name="accuracy"
        value={position.coords.accuracy}
    />
    <input type="hidden" name="time" value={position.timestamp} />
    <p>Latitude: {position.coords.latitude}</p>
    <p>Longitude: {position.coords.longitude}</p>
    <p>Accuracy: {position.coords.accuracy}</p>
    <p>Time: {position.timestamp}</p>
{/if}
{#if positionError}
    <p>{positionError.message}</p>
{/if}