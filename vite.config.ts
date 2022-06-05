import { defineConfig } from 'vite'
import tailwindcss from 'tailwindcss'
import tailwindcssNesting from 'tailwindcss/nesting'
import autoprefixer from 'autoprefixer'
import laravel from 'vite-plugin-laravel'
import vue from '@vitejs/plugin-vue'
import inertia from './resources/scripts/vite/inertia-layout'
import path from "path";

export default defineConfig({
	plugins: [
		inertia(),
		vue(),
		laravel({
			postcss: [
				tailwindcssNesting(),
				tailwindcss(),
				autoprefixer(),
			],
		}),
	],
	resolve: {
    alias: {
      "@": path.resolve(__dirname, "./resources"),
			ziggy: path.resolve(__dirname, './vendor/tightenco/ziggy/src/js/vue'),
    },
  },
	optimizeDeps: {
    include: ["ziggy"],
  },
})
