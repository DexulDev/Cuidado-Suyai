import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    css: {
        preprocessorOptions: {
            css: {
                additionalData: `@import "resources/css/variables.css";`
            }
        }
    },
    /* server: {
        host: '', //<-- Llena las comillas con tu dirección IPv4 (Usa en terminal el comando ipconfig para encontrarla)
        cors: {
            origin: ['http://'], //<-- Usa tu dirección IPv4
            methods: ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
            credentials: true
        },
    }, */
});