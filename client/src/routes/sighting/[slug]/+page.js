/** @type {import('./$types').PageLoad} */
export const load = async ({ fetch, params }) => {
    // Chain the fetch and JSON promises so we await on both.
    const sighting = (async () => {
        try {
            const response = await fetch(
                `https://bsh23.brighton.domains/ci609/api/sightings/${params.slug}`,
            );
            if (response.status === 404) {
                throw new Error("No sighting found for the provided ID.");
            }

            return response.json();
        } catch (error) {
            // The fetch() method throws a type error for network errors.
            if (error instanceof TypeError) {
                throw new Error(
                    "You're offline. Could not load details for this sighting.",
                );
            } else {
                throw error;
            }
        }
    })();

    return { sighting };
};
