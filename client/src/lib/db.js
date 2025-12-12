export const dbPromise = new Promise((resolve, reject) => {
    const request = window.indexedDB.open("ZapApp", 5);
    request.onerror = () => {
        console.error("Why didn't you allow my web app to use IndexedDB?!");
    };

    request.onupgradeneeded = (event) => {
        const db = event.target.result;
        db.createObjectStore("sightings", { autoIncrement: true });
    };

    request.onsuccess = (event) => {
        const db = event.target.result;

        db.onerror = (event) => {
            reject(event.target.error);
        };

        console.info("New IDBDatabase connection!");

        resolve(db);
    };
});
