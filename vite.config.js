import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '')
    const url = new URL(env.APP_URL || 'http://localhost')
    const host = url.hostname

    return {
        server: {
            hmr: {
                host,
            },
        },
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
        ],
    }
});
