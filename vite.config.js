import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import eslint from 'vite-plugin-eslint';
import stylelint from 'vite-plugin-stylelint';
import react from '@vitejs/plugin-react'

export default defineConfig({
    server: {
        host: '0.0.0.0',
    },
    plugins: [
        laravel({
            input: [
                'resources/scss/app.scss',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
        eslint({
            failOnError: true,
            exclude:[
                '**/node_modules/**',
                'vite.config.js',
                'vitest.config.js'
            ]
        }),
        stylelint({
            emitWarningAsError: true,
            include: [
                'resources/**/*.scss'
            ]
        }),
        react(),
    ],
    css: {
        modules: {
            localsConvention: "camelCase",
            generateScopedName: "[name]__[local]__[hash:base64:5]",
        }
    },
    test: {
        globals: true,
        environment: 'happy-dom',
    },
});
