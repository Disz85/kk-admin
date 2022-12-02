import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import eslint from 'vite-plugin-eslint';
import stylelint from 'vite-plugin-stylelint';
import react from '@vitejs/plugin-react'
import path from "path";
import crypto from "crypto";

const config = {
    server: {
        host: '0.0.0.0',
    },
    plugins: [
        laravel({
            input: [
                'resources/scss/app.scss',
                'resources/js/App.jsx'
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
            modules: {
                localsConvention: "camelCase",
                generateScopedName: "[name]__[local]__[hash:base64:5]",
            }
        }
    },
    test: {
        globals: true,
        environment: 'happy-dom',
    },
    build: {
        rollupOptions: {
            output: {
                assetFileNames: (assetInfo) => {
                    const { name } = assetInfo;
                    let extType = name.slice(name.lastIndexOf('.')).replace('.', '');
                    const route = path.dirname(name);
                    const subPath = route.slice(route.search(extType)).replace(`${extType}/`, "");
                    if (/png|jpe?g|svg|gif|tiff|bmp|ico/i.test(extType)) {
                        extType = 'img';
                    }

                    const partOfPath = subPath === extType ? '' : `/${subPath}`

                    return `assets/${extType}${partOfPath}/[name].[hash][extname]`;
                },
                chunkFileNames: 'assets/js/[${name}].[hash].js',
                entryFileNames: 'assets/js/[name].[hash].js',
            },
        },
    }
};

export default defineConfig(({ command, mode, ssrBuild }) => {
    if (mode === 'development' || mode === 'production') {
        return config;
    }
});
