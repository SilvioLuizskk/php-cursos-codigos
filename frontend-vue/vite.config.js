import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import { resolve } from "path";

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [vue()],
    css: {
        postcss: "./postcss.config.js",
    },
    server: {
        port: 5173,
        host: true,
        strictPort: false,
        hmr: {
            overlay: false,
        },
    },
    build: {
        outDir: "dist",
        assetsDir: "assets",
        sourcemap: false,
        minify: "terser",
    },
    resolve: {
        alias: {
            "@": resolve(__dirname, "src"),
        },
    },
});
