import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // CSS Files
                'resources/css/app.css',
                'resources/css/footer.css',
                'resources/css/layout-fixes.css',
                'resources/css/header-footer-fix.css',
                'resources/css/modal-fixes.css',
                'resources/css/cross-page.css',
                'resources/css/admin-login.css',
                'resources/css/theme.css',
                'resources/css/admin-fixes.css',
                'resources/css/modal-emergency.css',
                'resources/css/modal-z-index.css',
                'resources/css/z-index-hierarchy.css',
                
                // JavaScript Files
                'resources/js/app.js',
                'resources/js/modal-sin-backdrop.js',
                'resources/js/aria-focus-enhance.js',
                'resources/js/blade-modal-aria-fix.js'
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
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['vue'],
                    admin: [
                        'resources/js/components/admin/AdminDashboard.vue',
                        'resources/js/components/admin/SearchAnalyticsModal.vue',
                        'resources/js/components/admin/PasswordManagerFixed.vue'
                    ]
                }
            }
        }
    }
});