<script>
    let { ...props } = $props();

    // This component is a bit of a state machine. It could be cleaned up if the "change" event on PermissionStatus
    // actually worked in Safari, but alas, it's broken: https://bugs.webkit.org/show_bug.cgi?id=285457

    let geolocationEnabled = $state(false);
    let position = $state.raw();
    let positionError = $state();

    let watchID;
    const tryEnablingGeolocation = () => {
        geolocationEnabled = true;
        // Remove any previous watch handler.
        navigator.geolocation.clearWatch(watchID);

        // Get an initial position value while we wait for the watch handler to fire.
        navigator.geolocation.getCurrentPosition(
            (currentPosition) => position = currentPosition,
            (error) => positionError = error,
            {enableHighAccuracy: true},
        );

        watchID = navigator.geolocation.watchPosition(
            (currentPosition) => (position = currentPosition),
            (error) => {
                positionError = error;
                geolocationEnabled = false;
            },
            {enableHighAccuracy: true},
        );
    }

    const denied = () => {
        positionError = new Error("You didn't grant location permissions.");
        geolocationEnabled = false;
    }

    const updateStatus = () => {
        navigator.permissions.query({ name: "geolocation" }).then((permissionStatus) => {
            if (permissionStatus.state === "granted" || permissionStatus.state === "prompt") {
                tryEnablingGeolocation();
            } else if (permissionStatus.state === "denied") {
                denied();
            }
        });
    }
    const checkboxLifecycle = () => {
        // Set the checkbox's initial "checked" attribute to reflect whether geolocation permissions are enabled.
        updateStatus();

        // Teardown function
        return () => {
            // Remove watch handler.
            navigator.geolocation.clearWatch(watchID);
        };
    }
</script>

<input type="checkbox" onchange={updateStatus} bind:checked={geolocationEnabled} {@attach checkboxLifecycle} {...props}>
{#if positionError}
    <p>{positionError.message}</p>
{/if}
{#if position}
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