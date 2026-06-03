import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/public.css',
                'resources/js/app.js',
                'resources/js/landing.js',
                'resources/js/mapa-publico.js',
                'resources/js/public-animations.js',
                'resources/js/reportar.js',
                'resources/js/pqrs-pin-map.js',
                'resources/js/consulta-map.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
