/** @type {import('./$types').PageLoad} */
export async function load({ fetch, params }) {

    const sightings = new Promise((resolve, reject) => {
        const request = window.indexedDB.open("ZapApp", 5);
        request.onerror = (event) => {
            console.error("Why didn't you allow my web app to use IndexedDB?!");
        }

        request.onupgradeneeded = (event) => {
            const db = event.target.result;

            const objectStore = db.createObjectStore("sightings", { autoIncrement: true });
        }

        request.onsuccess = (event) => {
            const db = event.target.result;

            db.onerror = (event) => {
                reject(event.target.error);
            };

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
        }
    });

    return { sightings };
}
