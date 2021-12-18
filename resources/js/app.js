
import { createApp, h } from 'vue'
import { InertiaProgress } from '@inertiajs/progress'
import { createInertiaApp } from '@inertiajs/inertia-vue3'

InertiaProgress.init()

createInertiaApp({
  resolve: name => require(`./Pages/${name}`),
  title: title => `${title} - KnockKnock`,
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })

    app.config.globalProperties.$route = window.route

    app.use(plugin)
      .mount(el)
  },
})
