import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/css/admin-login.css',
                'resources/js/app.js'
            ],
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
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
            '@': path.resolve(__dirname, 'resources/js'),
        }
    },
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