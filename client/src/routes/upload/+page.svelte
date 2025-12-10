<script>
    import { dbPromise } from "$lib/db.js";
    import { goto } from "$app/navigation";
    import BlueButton from "$lib/components/BlueButton.svelte";

    let { data } = $props();

    let position = $state.raw(null);
    let positionError = $state("");

    function getGeolocation() {
        navigator.geolocation.getCurrentPosition(
            (currentPosition) => (position = currentPosition),
            (error) => (positionError = error.message),
            { enableHighAccuracy: true },
        );
    }

    async function handleSubmit(event) {
        event.preventDefault();
        const data = new FormData(event.currentTarget);

        let offlineSighting;
        // FormData doesn't support cloning, so take each key/val pair one-by-one.
        for (const [key, value] of data.entries()) {
            offlineSighting = { ...offlineSighting, [key]: value };
        }

        // TODO: experiment with higher durability
        const db = await dbPromise;
        const localIDPromise = new Promise((resolve) => {
            const idbRequest = db
                .transaction("sightings", "readwrite")
                .objectStore("sightings")
                .add(offlineSighting);

            idbRequest.onsuccess = () => {
                console.info(
                    `Added offline sighting with key: ${idbRequest.result}. :)`,
                );
                // Return the local ID of the offline pangolin sighting.
                resolve(idbRequest.result);
            };
        });

        try {
            const response = await fetch(event.currentTarget.action, {
                method: "POST",
                body: data,
            });

            if (response.ok) {
                // If the pangolin sighting upload was successful, we have no need for the offline sighting anymore, so
                // we can delete it from IndexedDB.
                const localID = await localIDPromise;
                console.info(
                    `Removing offline sighting with key: ${localID}. :)`,
                );
                await new Promise((resolve) => {
                    const idbRequest = db
                        .transaction("sightings", "readwrite")
                        .objectStore("sightings")
                        .delete(localID);
                    idbRequest.onsuccess = resolve;
                });

                const { id } = await response.json();
                await goto(`/ci609/sighting/${id}`);
            }
        } catch (error) {
            // TODO: handle non-offline errors separately.
            console.log(error.message);
            await goto("/ci609/offline");
        }
    }
</script>

<main>
    <h2>Add new sightings</h2>
    <form
        action="https://bsh23.brighton.domains/ci609/api/sightings"
        method="POST"
        onsubmit={handleSubmit}
    >
        <label for="image">Image:</label>
        <!-- On all major mobile browsers capture="environment" will prompt the user to take a photo. -->
        <!-- See: https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Attributes/capture -->
        <input
            type="file"
            id="image"
            name="image"
            accept="image/png, image/jpeg"
            capture="environment"
            required
        />
        <label for="status">Pangolin status:</label>
        <select id="status" name="deathType">
            <optgroup label="Alive">
                <option value="">Alive</option>
            </optgroup>
            <optgroup label="Dead">
                <option value="fence">Caught on fence</option>
                <option value="fenceElectrocuted">Electrocuted on fence</option>
                <option value="road">Killed on road</option>
            </optgroup>
        </select>
        <label for="notes"
            >Additional details, such as the type of fence or road. (optional):</label
        >
        <textarea id="notes" name="notes"></textarea>
        <label for="location">Location:</label>
        <BlueButton type="button" id="location" onclick={getGeolocation}
            >Provide location</BlueButton
        >
        {#if positionError}
            <!-- TODO: Geolocation error handling could be better. -->
            <p>{positionError}</p>
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
        <input type="submit" />
    </form>
</main>

<style>
    form {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    select,
    textarea {
        border-radius: 0.5rem;
        border: 1px solid var(--surface-1);
        background-color: var(--base);
        color: var(--text);
    }

    #notes {
        min-height: 9rem;
        resize: vertical;
        padding: 0.5rem;
    }
</style>
