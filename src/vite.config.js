import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/scss/main.scss', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },

        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: {
            host: 'dev.melon-bytes.com',
            port: '443'
        },
    },
});
