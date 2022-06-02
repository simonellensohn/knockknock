import { createSSRApp, h } from 'vue'
import { renderToString } from '@vue/server-renderer'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import createServer from '@inertiajs/server'
import { ZiggyVue } from 'ziggy'
import route from 'ziggy'

createServer((page) =>
  createInertiaApp({
    page,
    render: renderToString,
    resolve: (name) => require(`./Pages/${name}`),
    title: (title) => (title ? `${title} - KnockKnock` : 'KnockKnock'),
    setup({ app, props, plugin }) {
      app.config.globalProperties.$route = route

      return createSSRApp({
        render: () => h(app, props),
      })
        .use(plugin)
        .use(ZiggyVue, page.props.ziggy)
    },
  }),
)
