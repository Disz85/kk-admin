import { configDefaults, defineConfig } from 'vitest/config'

const config = defineConfig({
    test: {
        exclude: [...configDefaults.exclude, 'packages/template/*'],
        threads: false,
        environment: 'happy-dom',
    },
})

export default config;
