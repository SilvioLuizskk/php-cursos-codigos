addEventListener("fetch", (event) => {
    event.respondWith(handle(event.request));
});

async function handle(req) {
    const cache = caches.default;
    const cacheKey = new Request(req.url, { method: "GET" });
    let response = await cache.match(cacheKey);
    if (response) return response;
    response = await fetch(req);
    if (req.url.includes("/assets/")) {
        event.waitUntil(cache.put(cacheKey, response.clone()));
    }
    return response;
}
