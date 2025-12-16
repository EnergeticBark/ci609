<script>
    import { goto } from "$app/navigation";
    import { dbPromise } from "$lib/db.js";
    import SightingPreview from "$lib/components/SightingPreview.svelte";
    import BlueButton from "$lib/components/BlueButton.svelte";
    import LoadingIndicator from "$lib/components/LoadingIndicator.svelte";

    let uploading = $state(false);

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
        return fetch("https://bsh23.brighton.domains/ci609/api/sightings", {
            method: "POST",
            body: formData,
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
        // Ignore the click if we're already uploading.
        if (uploading) {
            return;
        }

        uploading = true;

        const db = await dbPromise;
        const sightings = await new Promise((resolve) => {
            let sightings;
            db
                .transaction("sightings", "readonly")
                .objectStore("sightings")
                .openCursor().onsuccess = (event) => {
                const cursor = event.target.result;
                if (cursor) {
                    sightings = { ...sightings, [cursor.key]: cursor.value };
                    cursor.continue();
                } else {
                    resolve(sightings);
                }
            };
        });

        for (const [localID, sighting] of Object.entries(sightings)) {
            const formData = sightingToFormData(sighting);
            await uploadAndDelete(formData, localID);
        }

        await goto("/ci609/");
    }

    let { data } = $props();
</script>

<main>
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
        <div id="gallery">
            {#each sightings as sighting}
                <SightingPreview href="#" {...sighting} />
            {/each}
        </div>
    {:catch error}
        <p>{error.message}</p>
    {/await}
</main>

<style>
    main {
        padding: 0 1rem 1.5rem;
    }

    @media (width >= 40rem) {
        main {
            padding: 0 3rem 1.5rem;
        }
    }
</style>
