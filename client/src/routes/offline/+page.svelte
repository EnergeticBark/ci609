<script>
    import {goto, invalidateAll} from "$app/navigation";
    import { dbPromise } from "$lib/db.js";
    import SightingPreview from "$lib/components/SightingPreview.svelte";
    import BlueButton from "$lib/components/BlueButton.svelte";
    import LoadingIndicator from "$lib/components/LoadingIndicator.svelte";
    import Gallery from "$lib/components/Gallery.svelte";

    let uploading = $state(false);
    let uploadError = $state();

    const getIndexedDBSightings = async () => {
        const db = await dbPromise;
        return new Promise((resolve) => {
            let sightings;
            db
                .transaction("sightings", "readonly")
                .objectStore("sightings")
                .openCursor().onsuccess = (event) => {
                const cursor = event.target.result;
                // Append each sighting to the sightings object.
                if (cursor) {
                    sightings = { ...sightings, [cursor.key]: cursor.value };
                    cursor.continue();
                } else {
                    resolve(sightings);
                }
            };
        });
    }

    // Convert the sighting object from IndexedDB back into a FormData.
    const sightingToFormData = (sighting) => {
        const formData = new FormData();
        for (const [inputName, value] of Object.entries(sighting)) {
            formData.append(inputName, value);
        }
        return formData;
    }

    // Upload the sighting to the API.
    const deleteLocal = async (localID) => {
        const db = await dbPromise;
        console.info(`Removing offline sighting with key: ${localID}. :)`);
        await new Promise((resolve) => {
            db
                .transaction("sightings", "readwrite")
                .objectStore("sightings")
                .delete(Number(localID)).onsuccess = resolve;
        });
    }

    const handleUploadAll = async () => {
        // Ignore the click if we're already uploading.
        if (uploading) {
            return;
        }

        uploading = true;
        uploadError = undefined;

        const sightings = await getIndexedDBSightings();
        for (const [localID, sighting] of Object.entries(sightings)) {
            const formData = sightingToFormData(sighting);
            // Upload the sighting to the API and only delete it from IndexedDB if the upload was successful.
            try {
                const response = await fetch("https://bsh23.brighton.domains/ci609/api/sightings", {
                    method: "POST",
                    body: formData,
                });

                if (response.ok) {
                    await deleteLocal(localID);
                }
            } catch (error) {
                // The fetch() method throws a type error for network errors.
                if (error instanceof TypeError) {
                    error = new Error(
                        `You appear to have gone offline.\n
                        Rest assured, any locally deleted sightings were uploaded successfully.`
                    );
                }

                uploading = false;
                uploadError = error;

                // Rerun this page's load function, which will update the gallery.
                await invalidateAll();
                return;
            }
        }

        // The sightings uploaded successfully, so redirect to the online sightings page to show them to the user.
        await goto("/ci609/");
    }

    let { data } = $props();
</script>

<h2>Your offline pangolin sightings</h2>
{#await data.sightings}
    <LoadingIndicator>Loading offline pangolin sightings...</LoadingIndicator>
{:then sightings}
    <BlueButton type="button" onclick={handleUploadAll}
        >Upload All</BlueButton
    >
    {#if uploading}
        <LoadingIndicator>Uploading...</LoadingIndicator>
    {/if}
    {#if uploadError}
        <p>{uploadError.message}</p>
    {/if}
    <Gallery>
        {#each sightings as sighting}
            <SightingPreview href="#" {...sighting} />
        {/each}
    </Gallery>
{:catch error}
    <p>{error.message}</p>
{/await}
