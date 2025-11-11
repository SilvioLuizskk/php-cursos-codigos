import { ref } from "vue";
import { adminService } from "@/services/adminService";

// Singleton pattern so multiple components reuse the same poller
const settings = ref({});
let intervalId = null;
let pollingMs = 5000;

const fetchSettings = async () => {
    try {
        const res = await adminService.getSettings();
        // adminService returns response.data normalized
        const data = res || {};

        // Normalize backend flat settings into nested shape that frontend expects
        const normalized = {
            ...data,
            theme: {
                primary_color:
                    data.primary_color ||
                    data.primaryColor ||
                    (data.theme && data.theme.primary_color) ||
                    null,
                secondary_color:
                    data.secondary_color ||
                    data.secondaryColor ||
                    (data.theme && data.theme.secondary_color) ||
                    null,
                logo_url:
                    data.logo_url ||
                    data.logoUrl ||
                    (data.theme && data.theme.logo_url) ||
                    null,
            },
            seo: {
                meta_description:
                    data.meta_description ||
                    data.metaDescription ||
                    (data.seo && data.seo.meta_description) ||
                    null,
            },
            social: {
                facebook:
                    data.facebook_url ||
                    data.facebook ||
                    (data.social && data.social.facebook) ||
                    null,
                instagram:
                    data.instagram_url ||
                    data.instagram ||
                    (data.social && data.social.instagram) ||
                    null,
            },
            payment_methods: data.payment_methods || data.paymentMethods || {},
        };

        settings.value = normalized;
        console.log("[useSettings] fetched settings ->", normalized);
    } catch (e) {
        console.warn("useSettings: fetchSettings failed", e);
    }
};

export function useSettings() {
    const startPolling = (ms = 5000) => {
        pollingMs = ms;
        if (intervalId) return;
        // initial fetch
        fetchSettings();
        intervalId = setInterval(fetchSettings, pollingMs);
    };

    const stopPolling = () => {
        if (intervalId) {
            clearInterval(intervalId);
            intervalId = null;
        }
    };

    const manualRefresh = async () => {
        await fetchSettings();
        return settings.value;
    };

    return {
        settings,
        startPolling,
        stopPolling,
        manualRefresh,
    };
}

export default useSettings;
