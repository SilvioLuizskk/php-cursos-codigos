import { ref, computed } from "vue";
import { useHome } from "@/composables/useHome";
import { useSettings } from "@/composables/useSettings";
import { adminService } from "@/services/adminService";

// Singleton pattern so multiple components share the same poller/state
let singleton = null;

export function useAdminSync() {
    if (singleton) return singleton;

    const home = useHome();
    const settings = useSettings();

    const pollingMs = ref(3000);
    let intervalId = null;

    // admin-side resources
    const adminProducts = ref([]);
    const adminBanners = ref([]);
    const adminCategories = ref([]);

    // helper: normalize admin products to what the client components expect
    const normalizeAdminProductsArray = (prods) => {
        const arr = Array.isArray(prods) ? prods : prods?.data || [];
        return arr.map((p) => {
            // prefer 'image' if present, otherwise extract first from 'images' array
            let image = null;
            if (p.image) image = p.image;
            else if (Array.isArray(p.images) && p.images.length > 0) {
                const first = p.images[0];
                image = first?.url || first?.path || first || null;
            }

            // Try multiple fields for name/title (portuguese/english variations)
            const name = p.name || p.title || p.nome || p.product_name || null;

            // description fallbacks
            const description = p.description || p.desc || p.resumo || null;

            // slug/id fallbacks
            const slug = p.slug || p.handle || p.id || null;
            const id = p.id || p._id || (slug ? slug : null);

            // is_featured fallbacks
            const is_featured =
                p.is_featured || p.featured || p.destaque || false;

            // normalize price to a string with 2 decimals if a numeric value is provided
            let price = p.price ?? p.valor ?? null;
            if ((price == null || price === "") && p.price_cents != null) {
                price = (Number(p.price_cents) / 100).toFixed(2);
            } else if (typeof price === "number") {
                price = price.toFixed(2);
            } else if (typeof price === "string") {
                // try to coerce brazilian formatted prices
                const cleaned = price
                    .replace(/[^0-9.,]/g, "")
                    .replace(/,/g, ".");
                const n = Number(cleaned);
                if (!Number.isNaN(n)) price = n.toFixed(2);
            }

            return {
                ...p,
                id,
                slug,
                name,
                description,
                image,
                price,
                is_featured,
            };
        });
    };

    const startPolling = (ms = 3000) => {
        pollingMs.value = ms;
        if (intervalId) return;
        // initial fetch
        home.fetchHomeData();
        settings.manualRefresh();

        // fetch admin resources (products, banners, categories)
        (async () => {
            try {
                const [prods, bans, cats] = await Promise.all([
                    adminService.getAdminProducts(),
                    adminService.getBanners(),
                    adminService.getCategories(),
                ]);
                adminProducts.value = normalizeAdminProductsArray(prods);
                adminBanners.value = Array.isArray(bans)
                    ? bans
                    : bans?.data || [];
                adminCategories.value = Array.isArray(cats)
                    ? cats
                    : cats?.data || [];
                console.log("[useAdminSync] initial admin fetch", {
                    products: adminProducts.value.length,
                    banners: adminBanners.value.length,
                    categories: adminCategories.value.length,
                });
                // sample outputs for quick debugging in client console
                try {
                    console.log(
                        "[useAdminSync] adminProducts sample ->",
                        adminProducts.value.slice(0, 3),
                    );
                    console.log(
                        "[useAdminSync] adminBanners sample ->",
                        adminBanners.value.slice(0, 3),
                    );
                    console.log(
                        "[useAdminSync] adminCategories sample ->",
                        adminCategories.value.slice(0, 3),
                    );
                } catch (e) {
                    // ignore
                }
            } catch (e) {
                console.warn("useAdminSync: initial admin fetch failed", e);
            }
        })();

        intervalId = setInterval(async () => {
            try {
                await Promise.all([
                    home.fetchHomeData(),
                    settings.manualRefresh(),
                    (async () => {
                        try {
                            const [prods, bans, cats] = await Promise.all([
                                adminService.getAdminProducts(),
                                adminService.getBanners(),
                                adminService.getCategories(),
                            ]);
                            const oldP = adminProducts.value;
                            const oldB = adminBanners.value;
                            const oldC = adminCategories.value;

                            adminProducts.value =
                                normalizeAdminProductsArray(prods);
                            adminBanners.value = Array.isArray(bans)
                                ? bans
                                : bans?.data || [];
                            adminCategories.value = Array.isArray(cats)
                                ? cats
                                : cats?.data || [];

                            if (adminProducts.value.length !== oldP.length) {
                                console.log(
                                    "[useAdminSync] adminProducts changed ->",
                                    adminProducts.value.length,
                                );
                            }
                            if (adminBanners.value.length !== oldB.length) {
                                console.log(
                                    "[useAdminSync] adminBanners changed ->",
                                    adminBanners.value.length,
                                );
                            }
                            if (adminCategories.value.length !== oldC.length) {
                                console.log(
                                    "[useAdminSync] adminCategories changed ->",
                                    adminCategories.value.length,
                                );
                            }
                        } catch (e) {
                            // ignore per-interval admin fetch errors
                        }
                    })(),
                ]);
                console.log(
                    "[useAdminSync] periodic fetch succeeded at",
                    new Date().toISOString(),
                );
            } catch (e) {
                // swallow - individual composables will surface errors
            }
        }, pollingMs.value);
    };

    const stopPolling = () => {
        if (intervalId) {
            clearInterval(intervalId);
            intervalId = null;
        }
    };

    singleton = {
        startPolling,
        stopPolling,
        homeData: home.homeData,
        // Prefer admin-provided lists (if not empty) so admin changes reflect immediately
        featuredProducts: computed(() => {
            // Temporary: prefer up to 8 most recent admin products (ignore is_featured)
            if (adminProducts.value && adminProducts.value.length > 0) {
                try {
                    const slice = adminProducts.value.slice(0, 8);
                    console.log(
                        "[useAdminSync] featuredProducts (admin) ->",
                        slice.length,
                    );
                    return slice;
                } catch (e) {
                    return adminProducts.value;
                }
            }
            return home.featuredProducts.value;
        }),
        categories: computed(() =>
            adminCategories.value && adminCategories.value.length > 0
                ? adminCategories.value
                : home.categories.value,
        ),
        stats: home.stats,
        banners: computed(() =>
            adminBanners.value && adminBanners.value.length > 0
                ? adminBanners.value
                : home.banners.value,
        ),
        loading: home.loading,
        error: home.error,
        settings: settings.settings,
        adminProducts,
        adminBanners,
        adminCategories,
        manualRefresh: async () => {
            await Promise.all([home.fetchHomeData(), settings.manualRefresh()]);
            try {
                const [prods, bans, cats] = await Promise.all([
                    adminService.getAdminProducts(),
                    adminService.getBanners(),
                    adminService.getCategories(),
                ]);
                adminProducts.value = normalizeAdminProductsArray(prods);
                adminBanners.value = Array.isArray(bans)
                    ? bans
                    : bans?.data || [];
                adminCategories.value = Array.isArray(cats)
                    ? cats
                    : cats?.data || [];
            } catch (e) {
                // ignore
            }
        },
    };

    return singleton;
}

export default useAdminSync;
