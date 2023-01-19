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
        //eslint({
        //    failOnError: true,
        //    exclude:[
        //        '**/node_modules/**',
        //        'vite.config.js',
        //        'vitest.config.js'
        //    ]
        //}),
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
            generateScopedName(name, cssFileName) {
                const scss = {
                    fileName : path.basename(cssFileName, ".module.scss"),
                    route : path.dirname(cssFileName),
                };

                const hash = crypto.createHash("shake256", { outputLength: 2 }).update(name).digest('hex');

                return `${scss.fileName}__${name}__${hash}`;
            }
        }
    },
    esbuild: {
        jsxFactory: 'h',
        jsxFragment: 'Fragment'
    },
    test: {
        globals: true,
        environment: 'happy-dom',
    },
};

const prodConfig = {
    css: {
        modules: {
            localsConvention: "camelCase",
            generateScopedName(name, cssFileName) {
                const scss = {
                    fileName : path.basename(cssFileName, ".module.scss?used"),
                    route : path.dirname(cssFileName),
                };

                const hash = crypto.createHash("shake256", { outputLength: 2 }).update(name).digest('hex');

                return `${scss.fileName}__${name}__${hash}`;
            }
        }
    },
    build: {
        sourcemap: true,
    }
}

export default defineConfig(({ command, mode, ssrBuild }) => {
    if (mode === 'development') {
        return config;
    }

    if (mode === 'production') {
        return {...config, ...prodConfig}
    }
});
