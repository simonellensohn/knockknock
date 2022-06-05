import { createApp, h } from 'vue'
import { InertiaProgress } from '@inertiajs/progress'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { ZiggyVue } from 'ziggy'
import route from 'ziggy'
import Layout from '@/Shared/Layout'

InertiaProgress.init()

createInertiaApp({
  resolve: (name) => {
    const page = require(`./Pages/${name}`).default

    if (page.name !== 'Login') {
      page.layout = page.layout || Layout
    }

    return page
  },
  title: (title) => (title ? `${title} - KnockKnock` : 'KnockKnock'),
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })

    app.config.globalProperties.$route = route

    app.use(plugin).use(ZiggyVue, props.initialPage.props.ziggy).mount(el)
  },
})
