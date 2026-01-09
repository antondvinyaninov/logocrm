import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { networkInterfaces } from 'os';

// Получаем локальный IP адрес
function getLocalIP() {
    const nets = networkInterfaces();
    for (const name of Object.keys(nets)) {
        for (const net of nets[name]) {
            // Пропускаем внутренние (localhost) и не IPv4 адреса
            if (net.family === 'IPv4' && !net.internal) {
                return net.address;
            }
        }
    }
    return 'localhost';
}

const localIP = getLocalIP();

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: localIP,
        },
        cors: true,
        strictPort: true,
    },
});
