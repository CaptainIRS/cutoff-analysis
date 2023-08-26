import { defineConfig } from "vite";
import laravel, { refreshPaths } from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/chart.js",
                // "vendor/filament/forms/dist/index.css"
            ],
            refresh: [
                ...refreshPaths,
                "app/Livewire/**",
                "app/Tables/Columns/**",
            ],
        }),
    ],
    build: {
        minify: "terser",
        // rollupOptions: {
        //     manualChunks: {
        //         alpinejs: ["alpinejs"],
        //         "alpinejs-focus": ["@alpinejs/focus"],
        //     },
        // },
    },
});
