import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
        VitePWA({
            registerType: 'autoUpdate',
            includeAssets: ['favicon.ico', 'images/logo.png'],
            manifest: {
                name: 'Rigel Coins',
                short_name: 'Rigel',
                description: 'Rigel Coins Mobile App',
                theme_color: '#ffffff',
                icons: [
                    {
                        src: 'images/logo.png',
                        sizes: '512x512',
                        type: 'image/png'
                    }
                ]
            }
        }),
    ],
    build: {
        // Production optimizations - use esbuild for faster builds
        minify: 'esbuild',
        target: 'es2015',
        // CSS optimization
        cssMinify: 'lightningcss',
        // No sourcemaps for production
        sourcemap: false,
    },
    server: {
        cors: true,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
    // Preload modules for faster initial load
    optimizeDeps: {
        include: ['alpinejs'],
    },
});
