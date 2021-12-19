<template>
  <div>
    <Head title="Dashboard" />

    <h1 class="mb-8 text-3xl font-bold">Dashboard</h1>

    <div class="grid grid-cols-3 gap-3 mb-8">
      <div class="p-4 border rounded shadow-sm">
        <div class="flex items-center">
          <div class="w-8 h-8 mr-3 bg-indigo-500 rounded shadow-inner" />

          <div class="flex flex-col">
            <span class="text-xs font-semibold text-gray-500">Total Rings</span>
            2
          </div>
        </div>
      </div>
      <div class="p-4 border rounded shadow-sm">
        <div class="flex items-center">
          <div class="w-8 h-8 mr-3 bg-indigo-500 rounded shadow-inner" />

          <div class="flex flex-col">
            <span class="text-xs font-semibold text-gray-500">Total Rings</span>
            2
          </div>
        </div>
      </div>
      <div class="p-4 border rounded shadow-sm">
        <div class="flex items-center">
          <div class="w-8 h-8 mr-3 bg-indigo-500 rounded shadow-inner" />

          <div class="flex flex-col">
            <span class="text-xs font-semibold text-gray-500">Total Rings</span>
            2
          </div>
        </div>
      </div>
    </div>

    <loading-button :loading="loading" class="p-4 mb-8 border" @click="togglePush">
      {{ isPushEnabled ? 'Ubsubscribe' : 'Subscribe' }}
    </loading-button>

    <ul>
      <li v-for="ring in rings" :key="ring.id">{{ ring.created_at }}</li>
    </ul>
  </div>
</template>

<script>
import { Head } from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/Layout'
import LoadingButton from '@/Shared/LoadingButton'

export default {
  components: {
    Head,
    LoadingButton,
  },

  layout: Layout,

  props: {
    rings: Array,
    auth: Object,
  },

  data() {
    return {
      loading: false,
      isPushEnabled: false,
      pushButtonDisabled: false,
    }
  },

  mounted() {
    this.registerServiceWorker()
  },

  methods: {
    registerServiceWorker() {
      if (!('serviceWorker' in navigator)) {
        console.log('Service workers aren\'t supported in this browser.')

        return
      }

      navigator.serviceWorker.register('/sw.js').then(() => this.initialiseServiceWorker())
    },

    initialiseServiceWorker() {
      if (!('showNotification' in ServiceWorkerRegistration.prototype)) {
        console.log('Notifications aren\'t supported.')

        return
      }

      if (Notification.permission === 'denied') {
        console.log('The user has blocked notifications.')

        return
      }

      if (!('PushManager' in window)) {
        console.log('Push messaging isn\'t supported.')

        return
      }

      navigator.serviceWorker.ready.then((registration) => {
        registration.pushManager
          .getSubscription()
          .then((subscription) => {
            this.pushButtonDisabled = false

            if (!subscription) {
              return
            }

            this.updateSubscription(subscription)
            this.isPushEnabled = true
          })
          .catch((e) => {
            console.log('Error during getSubscription()', e)
          })
      })
    },

    subscribe() {
      navigator.serviceWorker.ready.then((registration) => {
        const options = { userVisibleOnly: true }
        const vapidPublicKey = this.auth.vapidPublicKey

        if (vapidPublicKey) {
          options.applicationServerKey = this.urlBase64ToUint8Array(vapidPublicKey)
        }

        registration.pushManager
          .subscribe(options)
          .then((subscription) => {
            this.isPushEnabled = true
            this.pushButtonDisabled = false
            this.updateSubscription(subscription)
          })
          .catch((e) => {
            if (Notification.permission === 'denied') {
              console.log('Permission for Notifications was denied')
              this.pushButtonDisabled = true
            } else {
              console.log('Unable to subscribe to push.', e)
              this.pushButtonDisabled = false
            }
          })
      })
    },

    unsubscribe() {
      navigator.serviceWorker.ready.then((registration) => {
        registration.pushManager
          .getSubscription()
          .then((subscription) => {
            if (!subscription) {
              this.isPushEnabled = false
              this.pushButtonDisabled = false

              return
            }

            subscription
              .unsubscribe()
              .then(() => {
                this.deleteSubscription(subscription)
                this.isPushEnabled = false
                this.pushButtonDisabled = false
              })
              .catch((e) => {
                console.log('Unsubscription error: ', e)
                this.pushButtonDisabled = false
              })
          })
          .catch((e) => {
            console.log('Error thrown while unsubscribing.', e)
          })
      })
    },

    togglePush() {
      if (this.isPushEnabled) {
        this.unsubscribe()
      } else {
        this.subscribe()
      }
    },

    updateSubscription(subscription) {
      const key = subscription.getKey('p256dh')
      const token = subscription.getKey('auth')
      const contentEncoding = (PushManager.supportedContentEncodings || ['aesgcm'])[0]
      const data = {
        endpoint: subscription.endpoint,
        publicKey: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
        authToken: token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null,
        contentEncoding,
      }

      this.loading = true

      this.$inertia.post('/push/subscriptions', data, {
        onFinish: () => this.loading = false,
      })
    },

    deleteSubscription(subscription) {
      this.loading = true

      this.$inertia.post('/push/subscriptions/delete', { endpoint: subscription.endpoint }, {
        onFinish: () => this.loading = false,
      })
    },

    sendNotification() {
      this.loading = true

      this.$inertia
        .post('/api/notifications', {
          onFinish: () => this.loading = false,
          onError: (error) => console.log(error),
        })
    },
    /**
     * https://github.com/Minishlink/physbook/blob/02a0d5d7ca0d5d2cc6d308a3a9b81244c63b3f14/app/Resources/public/js/app.js#L177
     *
     * @param  {String} base64String
     * @return {Uint8Array}
     */
    urlBase64ToUint8Array(base64String) {
      const padding = '='.repeat((4 - (base64String.length % 4)) % 4)
      const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/')
      const rawData = window.atob(base64)
      const outputArray = new Uint8Array(rawData.length)
      for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i)
      }
      return outputArray
    },
  },
}
</script>
