import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/table.css',
                'resources/css/dashboard.css',
                'resources/css/sidebar.css',
                'resources/css/icon-color.css',
                'resources/css/search-result.css',
                'resources/js/app.js',
                'resources/js/devices/deviceTableSearch.js',
                'resources/js/devices/device-info.js',
                'resources/js/devices/deviceCreate.js',
                'resources/js/location.js',
                'resources/js/deviceType.js',
                'resources/js/table-resizer.js',
                'resources/js/entityActions.js',
                'resources/js/importExportHandlers.js',
                'resources/js/column-selector.js',
                'resources/js/button-group.js',
                'resources/js/tableHeader.js',
                'resources/js/mainSearch.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        minify: 'terser', // Terser kullanarak minify işlemi
        terserOptions: {
            compress: {
                drop_console: true, // Konsol çıktısını kaldırır
                drop_debugger: true, // Debugger ifadelerini kaldırır
            },
            mangle: true, // Koddaki değişken isimlerini karmaşıklaştırır
            format: {
                comments: false, // Yorumları kaldırır
            },
        },
    },
    server: {

    },
});
