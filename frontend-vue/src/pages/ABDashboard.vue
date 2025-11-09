<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">A/B Test Dashboard</h1>
        <div class="mb-4">
            <input
                v-model="testName"
                class="border p-2 rounded"
                placeholder="Test name (e.g. homepage_hero)"
            />
            <button
                @click="loadStats"
                class="ml-2 bg-blue-600 text-white px-3 py-1 rounded"
            >
                Carregar
            </button>
        </div>

        <div v-if="stats" class="bg-white p-4 rounded shadow">
            <h2 class="font-semibold mb-2">Teste: {{ stats.test }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div
                    v-for="(v, k) in stats.variants"
                    :key="k"
                    class="p-3 border rounded"
                >
                    <h3 class="font-bold">Variante {{ k }}</h3>
                    <p>Exposições: {{ v.exposures }}</p>
                    <p>Conversões: {{ v.conversions }}</p>
                    <p>
                        Taxa de conversão:
                        {{ v.conversion_rate_percent ?? "—" }}%
                    </p>
                </div>
            </div>
            <div class="mt-4">
                <h3 class="font-semibold">Totais</h3>
                <p>Exposições: {{ stats.totals.exposures }}</p>
                <p>Conversões: {{ stats.totals.conversions }}</p>
                <p>
                    Taxa Global:
                    {{ stats.totals.conversion_rate_percent ?? "—" }}%
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from "vue";

export default {
    setup() {
        const testName = ref("homepage_hero");
        const stats = ref(null);

        async function loadStats() {
            try {
                const res = await fetch(
                    `/api/ab/stats?test=${encodeURIComponent(testName.value)}`,
                );
                stats.value = await res.json();
            } catch (e) {
                console.error(e);
                alert(
                    "Erro ao carregar estatísticas (verifique backend e Redis)",
                );
            }
        }

        return { testName, stats, loadStats };
    },
};
</script>

<style scoped>
/* small styling */
</style>
