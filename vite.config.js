import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import inertia from './resources/scripts/vite/inertia-layout'
import path from 'path'

export default defineConfig({
  plugins: [
    inertia(),
    vue(),
    laravel({
      input: ['resources/css/app.css', 'resources/scripts/main.ts'],
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
