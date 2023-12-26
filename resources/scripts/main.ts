import { createSSRApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { ZiggyVue } from 'ziggy-js/dist/vue'
import { importPageComponent } from '@/scripts/vite/import-page-component'

createInertiaApp({
  resolve: name => importPageComponent(name),
  setup({ el, App, props, plugin }) {
    createSSRApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue, props.initialPage.props.ziggy)
      .mount(el)
  },
})
