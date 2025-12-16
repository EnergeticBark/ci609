<script>
    import { goto } from "$app/navigation";
    import { dbPromise } from "$lib/db.js";
    import FileLimitedSize from "./FileLimitedSize.svelte";
    import GeolocationInput from "./GeolocationInput.svelte";
    import StatusOption from "./StatusOption.svelte";

    let submitting = $state(false);

    async function handleSubmit(event) {
        event.preventDefault();
        // Ignore the click if we're already submitting.
        if (submitting) {
            return;
        }
        submitting = true;

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
            console.error(error.message);
            await goto("/ci609/offline");
        }
        submitting = false;
    }
</script>

<main>
    <h2>Record a new sighting</h2>
    <form
        action="https://bsh23.brighton.domains/ci609/api/sightings"
        method="POST"
        onsubmit={handleSubmit}
    >
        <fieldset>
            <legend>Pangolin details</legend>
            <label for="image">Image <em>(required)</em></label>
            <!-- On all major mobile browsers capture="environment" will prompt the user to take a photo. -->
            <!-- See: https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Attributes/capture -->
            <FileLimitedSize
                id="image"
                name="image"
                accept="image/png, image/jpeg"
                required
                maxsize="10"
            />
            <label for="status">Pangolin status</label>
            <select id="status" name="deathType">
                <optgroup label="Alive">
                    <StatusOption value="" />
                </optgroup>
                <optgroup label="Dead">
                    <StatusOption value="fence" />
                    <StatusOption value="fenceElectrocuted" />
                    <StatusOption value="road" />
                </optgroup>
            </select>
            <label for="notes"
                >Additional details, such as fence/road type:</label
            >
            <textarea id="notes" name="notes" maxlength="10000"></textarea>
        </fieldset>
        <fieldset>
            <legend>Pangolin location</legend>
            <GeolocationInput required />
        </fieldset>
        <input type="submit" />
        {#if submitting}
            <label>Submitting...<progress></progress></label>
        {/if}
    </form>
</main>

<style>
    main {
        width: 100%;
        max-width: 48rem;
        margin: 0 auto;
    }

    fieldset {
        display: flex;
        flex-direction: column;
        padding: 0.75rem 1rem 1rem;
        border: 0.1rem solid #bcc0cc;
        border-radius: 0.5rem;
        margin: 1rem 0;
    }

    legend {
        box-sizing: border-box;
        color: inherit;
        display: table;
        max-width: 100%;
        padding: 0;
        white-space: normal;
    }

    label:not(:first-of-type) {
        margin-top: 1rem;
    }

    select,
    textarea {
        border-radius: 0.5rem;
        border: 1px solid var(--surface-1);
        background-color: var(--base);
        color: var(--text);
    }

    select {
        height: 2.25rem;
    }

    #notes {
        min-height: 9rem;
        resize: vertical;
        padding: 0.5rem;
    }

    input[type="submit"] {
        height: 1.75rem;
    }
</style>
