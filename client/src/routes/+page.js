/** @type {import('./$types').PageLoad} */
export async function load({ fetch }) {
    // Chain the fetch and json promises so we await on both.
    const sightings = fetch('https://bsh23.brighton.domains/ci609/api/sightings')
        .then((res) => res.json());

    return { sightings };
}