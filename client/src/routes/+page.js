/** @type {import('./$types').PageLoad} */
export async function load() {
    const res = await fetch('/ci609/api/sightings');
    const sightings = await res.json();

    return { sightings };
}