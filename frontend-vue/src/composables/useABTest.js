// Composable: useABTest
// A/B testing helper: obtains a variant from backend (or local fallback),
// records conversions and queues them when offline.

export function useABTest() {
    // helper: ensure a visitor id cookie exists (ab_visitor)
    function ensureVisitorId() {
        const name = "ab_visitor";
        const matches = document.cookie.match(
            new RegExp("(?:^|; )" + name + "=([^;]*)"),
        );
        if (matches) return matches[1];
        // generate simple uuid v4
        const id =
            "v-" +
            ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, (c) =>
                (
                    c ^
                    (crypto.getRandomValues(new Uint8Array(1))[0] &
                        (15 >> (c / 4)))
                ).toString(16),
            );
        // set cookie for 30 days
        const expires = new Date(
            Date.now() + 30 * 24 * 60 * 60 * 1000,
        ).toUTCString();
        document.cookie = `${name}=${id}; path=/; expires=${expires}; SameSite=Lax`;
        return id;
    }

    // local queue for conversions when offline/fail
    function pushConversionToQueue(payload) {
        try {
            const key = "ab:queue";
            const raw = localStorage.getItem(key);
            const arr = raw ? JSON.parse(raw) : [];
            arr.push({ payload, ts: Date.now() });
            localStorage.setItem(key, JSON.stringify(arr));
        } catch (e) {
            // ignore
        }
    }

    async function flushQueue() {
        const key = "ab:queue";
        const raw = localStorage.getItem(key);
        if (!raw) return;
        let arr;
        try {
            arr = JSON.parse(raw);
        } catch (e) {
            return;
        }
        if (!arr.length) return;
        // try to send sequentially
        for (const item of arr.slice()) {
            try {
                const res = await fetch("/api/ab/convert", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    credentials: "same-origin",
                    body: JSON.stringify(item.payload),
                });
                if (res.ok) {
                    // remove first element
                    arr.shift();
                } else {
                    // stop on server error to avoid tight loop
                    break;
                }
            } catch (e) {
                break;
            }
        }
        try {
            localStorage.setItem(key, JSON.stringify(arr));
        } catch (e) {}
    }

    // try flush when online
    if (typeof window !== "undefined") {
        window.addEventListener &&
            window.addEventListener("online", () => {
                flushQueue();
            });
    }

    async function getVariant(testName) {
        const key = `ab:${testName}`;
        let variant = localStorage.getItem(key);
        if (variant) return variant;

        const visitor = ensureVisitorId();
        try {
            const url = `/api/ab/variant?test=${encodeURIComponent(testName)}&visitor=${encodeURIComponent(visitor)}`;
            const res = await fetch(url, { credentials: "same-origin" });
            if (!res.ok) throw new Error("network");
            const data = await res.json();
            variant = data.variant || "A";
            localStorage.setItem(key, variant);
            // try to flush any queued conversions
            flushQueue();
            return variant;
        } catch (err) {
            // fallback local random assignment
            variant = Math.random() < 0.5 ? "A" : "B";
            localStorage.setItem(key, variant);
            return variant;
        }
    }

    async function recordConversion(testName, variant, metadata = {}) {
        const payload = { test: testName, variant, metadata };
        try {
            const res = await fetch("/api/ab/convert", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                credentials: "same-origin",
                body: JSON.stringify(payload),
            });
            if (!res.ok) throw new Error("network");
            return true;
        } catch (e) {
            // queue for retry
            pushConversionToQueue(payload);
            return false;
        }
    }

    return { getVariant, recordConversion };
}
