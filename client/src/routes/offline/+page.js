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
                sightings.push({
                    id: cursor.key,
                    image: URL.createObjectURL(cursor.value.image),
                    deathType: cursor.value.deathType,
                    time: Number(cursor.value.time),
                });
                cursor.continue();
            } else {
                resolve(sightings);
            }
        };
    });

    return { sightings };
}
