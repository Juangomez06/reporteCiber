import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/auth.css', 'resources/js/app.js', 'resources/css/navigation.css', 'resources/js/dashboard-charts.js'],
            refresh: true,
        }),
    ],
});