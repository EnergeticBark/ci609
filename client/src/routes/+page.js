/** @type {import('./$types').PageLoad} */
export async function load({ fetch }) {
    // Chain the fetch and JSON promises together so we await on both in +page.svelte.
    const sightings = (async () => {
        try {
            let response = await fetch(
                "https://bsh23.brighton.domains/ci609/api/sightings",
            );
            if (response.status === 204) {
                throw new Error("There are no pangolin sightings yet.");
            }

            return await response.json();
        } catch (error) {
            // The fetch() method throws a type error for network errors.
            if (error instanceof TypeError) {
                throw new Error(
                    "You're offline, but you can save sightings to upload later.",
                );
            } else {
                throw error;
            }
        }
    })();

    return { sightings };
}
