import { createInertiaApp } from '@inertiajs/vue3'
import createServer from '@inertiajs/vue3/server'
import { renderToString } from '@vue/server-renderer'
import { createSSRApp, h } from 'vue'
import { ZiggyVue } from 'ziggy-js/dist/vue'
import { importPageComponent } from '@/scripts/vite/import-page-component'

createServer(page =>
  createInertiaApp({
    page,
    render: renderToString,
    resolve: name => importPageComponent(name),
    setup({ App, props, plugin }) {
      return createSSRApp({
        render: () => h(App, props),
      })
        .use(plugin)
        .use(ZiggyVue, page.props.ziggy)
    },
  }),
)
