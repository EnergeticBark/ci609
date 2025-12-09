import { dbPromise } from "$lib/db.js";

/** @type {import('./$types').PageLoad} */
export async function load() {
    const db = await dbPromise;
    const sightings = new Promise((resolve) => {
        const sightings = [];
        db
            .transaction("sightings", "readonly")
            .objectStore("sightings")
            .openCursor(null, "prev").onsuccess = (event) => {
            const cursor = event.target.result;
            if (cursor) {
                const imageURL = URL.createObjectURL(cursor.value.image);
                sightings.push({
                    id: cursor.key,
                    ...cursor.value,
                    image: imageURL,
                });
                cursor.continue();
            } else {
                resolve(sightings);
            }
        };
    });

    return { sightings };
}
