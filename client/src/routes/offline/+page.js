import { dbPromise } from "$lib/db.js";

/** @type {import('./$types').PageLoad} */
export const load = async () => {
    const db = await dbPromise;
    const sightings = new Promise((resolve, reject) => {
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
                if (sightings.length === 0) {
                    reject(new Error("No offline pangolin sightings yet."));
                }

                resolve(sightings);
            }
        };
    });

    return { sightings };
};
