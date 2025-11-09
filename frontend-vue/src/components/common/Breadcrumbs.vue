<template>
    <nav class="flex text-sm text-gray-500" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1">
            <li
                v-for="(item, idx) in items"
                :key="idx"
                class="inline-flex items-center"
            >
                <template v-if="idx > 0">
                    <svg
                        class="w-4 h-4 mx-1"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7"
                        />
                    </svg>
                </template>
                <router-link
                    v-if="item.to"
                    :to="item.to"
                    class="hover:text-blue-600"
                    >{{ item.label }}</router-link
                >
                <span v-else>{{ item.label }}</span>
            </li>
        </ol>
    </nav>
</template>

<script setup>
defineProps({
    items: {
        type: Array,
        default: () => [],
    },
});
</script>
