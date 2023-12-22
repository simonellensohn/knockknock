import { createSSRApp, h } from 'vue'
import createServer from '@inertiajs/server'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { ZiggyVue } from 'ziggy-js/dist/vue'
import { importPageComponent } from '@/scripts/vite/import-page-component'

createServer(page =>
  createInertiaApp({
    page,
    resolve: name => importPageComponent(name, import.meta.glob('../views/pages/**/*.vue')),
    setup({ el, app, props, plugin }) {
      createSSRApp({ render: () => h(app, props) })
        .use(plugin)
        .use(ZiggyVue, page.props.ziggy)
        .mount(el)
    },
  }),
)
