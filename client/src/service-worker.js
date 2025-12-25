// Disables access to DOM typings like `HTMLElement` which are not available
// inside a service worker and instantiates the correct globals
/// <reference no-default-lib="true"/>
/// <reference lib="esnext" />
/// <reference lib="webworker" />

// Ensures that the `$service-worker` import has proper type definitions
/// <reference types="@sveltejs/kit" />

// Only necessary if you have an import from `$env/static/public`
/// <reference types="../.svelte-kit/ambient.d.ts" />

import { base, build, files, version } from "$service-worker";

// This gives `self` the correct types
const self = /** @type {ServiceWorkerGlobalScope} */ (
    /** @type {unknown} */ (globalThis.self)
);

// Create a unique cache name for this deployment
const CACHE = `cache-${version}`;

const ASSETS = [
    `${base}/`,
    ...build, // the app itself
    ...files, // everything in `static`
];

self.addEventListener("install", (event) => {
    // Create a new cache and add all files to it
    const addFilesToCache = async () => {
        const cache = await caches.open(CACHE);
        return cache.addAll(ASSETS);
    };

    event.waitUntil(addFilesToCache());
});

self.addEventListener("activate", (event) => {
    // Remove previous cached data from disk
    const deleteOldCaches = async () => {
        for (const key of await caches.keys()) {
            if (key !== CACHE) await caches.delete(key);
        }
        // Start using this service worker now instead of on the next reload.
        return clients.claim();
    };

    event.waitUntil(deleteOldCaches());
});

self.addEventListener("fetch", (event) => {
    // Ignore non-GET requests
    if (event.request.method !== "GET") return;

    const respond = async () => {
        const url = new URL(event.request.url);
        const cache = await caches.open(CACHE);

        // `build`/`files` can always be served from the cache
        if (ASSETS.includes(url.pathname)) {
            const response = await cache.match(url.pathname);

            if (response) {
                return response;
            }
        }

        // Respond to all navigate requests with our app's root
        // This behavior is similar to a NavigationRoute if I had used Workbox.
        // See: https://developer.chrome.com/docs/workbox/modules/workbox-routing#how_to_register_a_navigation_route
        if (
            event.request.mode === "navigate" &&
            url.pathname.startsWith(`${base}/api`) === false
        ) {
            const response = await cache.match(`${base}/`);

            if (response) {
                return response;
            }
        }

        // Fall back to the network
        return fetch(event.request);
    };

    event.respondWith(respond());
});
