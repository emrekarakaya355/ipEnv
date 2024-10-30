import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/search.js',
                'resources/js/deviceCreate.js',
                'resources/js/location.js',
                'resources/js/deviceType.js',
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
