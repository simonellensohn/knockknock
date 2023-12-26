import path from 'node:path'
import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [
    vue(),
    laravel({
      input: ['resources/css/app.css', 'resources/scripts/main.ts'],
      ssr: 'resources/scripts/ssr.ts',
      refresh: true,
    }),
  ],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './resources'),
      '@components': path.resolve(__dirname, './resources/views/components'),
      '@layouts': path.resolve(__dirname, './resources/views/layouts'),
      '@pages': path.resolve(__dirname, './resources/views/pages'),
    },
  },
})
