
import { createApp, h } from 'vue'
import { InertiaProgress } from '@inertiajs/progress'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import * as PusherPushNotifications from '@pusher/push-notifications-web';

// const beamsClient = new PusherPushNotifications.Client({
//   instanceId: 'ac8a0c5d-25e3-4965-b7fd-f32e8d51f2f0',
// });

// beamsClient.start()
//     .then(() => beamsClient.addDeviceInterest('hello'))
//     .then(() => console.log('Successfully registered and subscribed!'))
//     .catch(console.error);

InertiaProgress.init()

createInertiaApp({
  resolve: name => require(`./Pages/${name}`),
  title: title => `${title} - KnockKnock`,
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })

    app.config.globalProperties.$route = route

    app.use(plugin)
      .mount(el)
  },
})
