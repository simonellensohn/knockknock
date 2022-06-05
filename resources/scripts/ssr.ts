import { createSSRApp, h } from 'vue'
import createServer from '@inertiajs/server'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { importPageComponent } from '@/scripts/vite/import-page-component'
import { ZiggyVue } from 'ziggy';

createServer((page) => createInertiaApp({
	resolve: (name) => importPageComponent(name, import.meta.glob('../views/pages/**/*.vue')),
	setup({ el, app, props, plugin }) {
		createSSRApp({ render: () => h(app, props) })
			.use(plugin)
			.use(ZiggyVue, props.initialPage.props.ziggy)
			.mount(el)
	},
}))
