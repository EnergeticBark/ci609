/** @type {import('./$types').PageLoad} */
export async function load({ fetch, params }) {
    // Chain the fetch and JSON promises so we await on both.
    const sighting = fetch(`https://bsh23.brighton.domains/ci609/api/sightings/${params.slug}`)
        .then((res) => res.json());

    return { sighting };
}