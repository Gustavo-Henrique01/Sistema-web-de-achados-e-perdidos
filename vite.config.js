import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', // Seu arquivo CSS principal
                'resources/js/app.js',   // Seu arquivo JavaScript principal
                'vendor/munafio/chatify/public/css/app.css', // CSS do Chatify
                'vendor/munafio/chatify/public/js/app.js',   // JavaScript do Chatify
            ],
            refresh: true,
        }),
    ],
});