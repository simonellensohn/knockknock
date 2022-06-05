import { createSSRApp, h } from 'vue'
import { renderToString } from '@vue/server-renderer'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import createServer from '@inertiajs/server'
import { ZiggyVue } from 'ziggy'
import Layout from '@/Shared/Layout'

createServer((page) =>
  createInertiaApp({
    page,
    render: renderToString,
    resolve: (name) => {
      const page = require(`./Pages/${name}`).default

      if (page.name !== 'Login') {
        page.layout = page.layout || Layout
      }

      return page
    },
    title: (title) => (title ? `${title} - KnockKnock` : 'KnockKnock'),
    setup({ app, props, plugin }) {
      return createSSRApp({
        render: () => h(app, props),
      })
        .use(plugin)
        .use(ZiggyVue, page.props.ziggy)
    },
  }),
)
