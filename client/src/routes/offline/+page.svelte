<script>
    import SightingPreview from "$lib/components/SightingPreview.svelte";
    import BlueButton from "$lib/components/BlueButton.svelte";
    import { dbPromise } from "$lib/db.js";

    // Convert the sighting object from IndexedDB back into a FormData.
    function sightingToFormData(sighting) {
        const formData = new FormData();
        for (const [inputName, value] of Object.entries(sighting)) {
            formData.append(inputName, value);
        }
        return formData;
    }

    // Upload the sighting to the API.
    async function upload(formData) {
        return fetch('https://bsh23.brighton.domains/ci609/api/sightings', {
            method: 'POST',
            body: formData
        });
    }

    // Upload the sighting to the API.
    async function deleteLocal(localID) {
        const db = await dbPromise;
        console.info(`Removing offline sighting with key: ${localID}. :)`);
        await new Promise((resolve) => {
            db
                .transaction("sightings", "readwrite")
                .objectStore("sightings")
                .delete(Number(localID)).onsuccess = resolve;
        });
    }

    // Upload the sighting to the API.
    async function uploadAndDelete(formData, localID) {
        try {
            const response = await upload(formData);

            if (response.ok) {
                await deleteLocal(localID);
            }
        } catch (error) {
            console.error(error.message);
        }
    }

    async function handleUploadAll() {
        const db = await dbPromise;
        const sightings = await new Promise((resolve) => {
            let sightings;
            db
                .transaction("sightings", "readonly")
                .objectStore("sightings")
                .openCursor().onsuccess = (event) => {
                const cursor = event.target.result;
                if (cursor) {
                    sightings = {...sightings, [cursor.key]: cursor.value};
                    cursor.continue();
                } else {
                    resolve(sightings);
                }
            };
        });

        for (const [localID, sighting] of Object.entries(sightings)) {
            const formData = sightingToFormData(sighting);
            await uploadAndDelete(localID, formData);
        }
    }

    let { data } = $props();
</script>

<main>
    <h2>Your offline pangolin sightings</h2>
    <BlueButton type="button" onclick={handleUploadAll}>Upload All</BlueButton>
    {#await data.sightings}
        <label>Loading offline pangolin sightings...<progress></progress></label>
    {:then sightings}
        <div id="gallery">
            {#each sightings as sighting}
                <SightingPreview href="#" {...sighting} />
            {/each}
        </div>
    {/await}
</main>