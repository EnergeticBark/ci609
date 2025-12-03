/** @type {import('./$types').PageLoad} */
export async function load() {
    // Chain the fetch and json promises so we await on both.
    const sightings = fetch('/ci609/api/sightings')
        .then((res) => res.json());

    return { sightings };
}