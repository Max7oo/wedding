import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'

// https://vite.dev/config/
export default defineConfig({
  plugins: [vue(), vueDevTools()],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
    },
  },
  server: {
    proxy: {
      //TODO, bij mij werkte het op deze manier, maar weet niet of het ook zo bij jou werkt. Let op, je moet hiervoor wel XAMPP aan hebben staan.
      '^/contact\\.php$': {
        target: 'http://localhost',
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/contact\.php$/, '/wedding/contact.php'),
      },
    },
  },
})
