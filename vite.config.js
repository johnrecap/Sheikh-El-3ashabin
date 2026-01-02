import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/navbar.css',
                'resources/css/tailwind.css',
                'resources/css/chatfile.css',
                'resources/css/custom.css',
                'resources/js/app.js'],
            refresh: false,
        }),
    ],
    server: {
        host: '0.0.0.0',
        hmr: {
            host: 'localhost'
        },
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js/languages'),
        },
    },
    json: {
        namedExports: true,
        stringify: false,
    },
    optimizeDeps: {
        include: ["quill"]
    }
});
