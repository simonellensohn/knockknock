<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head, Link } from '@inertiajs/inertia-vue3'
import Icon from '@/views/components/Icon.vue'
import LoadingButton from '@/views/components/LoadingButton.vue'

const props = defineProps({
  auth: Object,
  lastRing: Object,
  totalRings: Number,
  averageVolume: Number,
})

const loading = ref(false)
const isPushEnabled = ref(false)
const pushButtonDisabled = ref(false)

onMounted(() => registerServiceWorker())

function registerServiceWorker() {
  if (!('serviceWorker' in navigator)) {
    console.log('Service workers aren\'t supported in this browser.')

    return
  }

  navigator.serviceWorker.register('/sw.js').then(() => initialiseServiceWorker())
}

function initialiseServiceWorker() {
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
        pushButtonDisabled.value = false

        if (!subscription)
          return

        updateSubscription(subscription)

        isPushEnabled.value = true
      })
      .catch((e) => {
        console.log('Error during getSubscription()', e)
      })
  })
}

function subscribe() {
  navigator.serviceWorker.ready.then((registration) => {
    const options = {
      userVisibleOnly: true,
      applicationServerKey: urlBase64ToUint8Array(props.auth?.vapidPublicKey),
    }

    registration.pushManager
      .subscribe(options)
      .then((subscription) => {
        isPushEnabled.value = true
        pushButtonDisabled.value = false

        updateSubscription(subscription)
      })
      .catch((e) => {
        if (Notification.permission === 'denied') {
          console.log('Permission for Notifications was denied')

          pushButtonDisabled.value = true
        }
        else {
          console.log('Unable to subscribe to push.', e)

          pushButtonDisabled.value = false
        }
      })
  })
}

function unsubscribe() {
  navigator.serviceWorker.ready.then((registration) => {
    registration.pushManager
      .getSubscription()
      .then((subscription) => {
        if (!subscription) {
          isPushEnabled.value = false
          pushButtonDisabled.value = false

          return
        }

        subscription
          .unsubscribe()
          .then(() => {
            deleteSubscription(subscription)

            isPushEnabled.value = false
            pushButtonDisabled.value = false
          })
          .catch((e) => {
            console.log('Unsubscription error: ', e)

            pushButtonDisabled.value = false
          })
      })
      .catch((e) => {
        console.log('Error thrown while unsubscribing.', e)
      })
  })
}

function togglePush() {
  if (isPushEnabled.value)
    unsubscribe()
  else
    subscribe()
}

function updateSubscription(subscription) {
  const key = subscription.getKey('p256dh')
  const token = subscription.getKey('auth')
  const contentEncoding = (PushManager.supportedContentEncodings || ['aesgcm'])[0]
  const data = {
    endpoint: subscription.endpoint,
    publicKey: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
    authToken: token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null,
    contentEncoding,
  }

  loading.value = true

  Inertia.put('/user/push/subscriptions', data, {
    onFinish: () => (loading.value = false),
  })
}

function deleteSubscription(subscription) {
  loading.value = true

  Inertia.delete(
    '/user/push/subscriptions/delete',
    {
      endpoint: subscription.endpoint,
      onFinish: () => (loading.value = false),
    },
  )
}

/**
 * https://github.com/Minishlink/physbook/blob/02a0d5d7ca0d5d2cc6d308a3a9b81244c63b3f14/app/Resources/public/js/app.js#L177
 *
 * @param  {string} base64String
 * @return {Uint8Array}
 */
function urlBase64ToUint8Array(base64String) {
  const padding = '='.repeat((4 - (base64String.length % 4)) % 4)
  const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/')
  const rawData = window.atob(base64)
  const outputArray = new Uint8Array(rawData.length)
  for (let i = 0; i < rawData.length; ++i)
    outputArray[i] = rawData.charCodeAt(i)

  return outputArray
}
</script>

<template layout>
  <Head title="Dashboard" />

  <h1 class="mb-8 text-3xl font-bold">
    Stats
  </h1>

  <div class="mb-16 grid grid-cols-1 gap-3 sm:grid-cols-3">
    <div class="rounded border p-4 shadow-sm">
      <Link
        href="/rings"
        class="flex items-center"
      >
        <div class="mr-3 flex h-8 w-8 items-center justify-center rounded bg-indigo-500 shadow-inner">
          <Icon
            name="bell"
            class="h-5 w-5 text-white"
          />
        </div>

        <div class="flex flex-col">
          <span class="text-xs font-semibold text-gray-500">Total Rings</span>
          {{ totalRings }}
        </div>
      </Link>
    </div>

    <div class="rounded border p-4 shadow-sm">
      <Link
        href="/rings"
        class="flex items-center"
      >
        <div class="mr-3 flex h-8 w-8 items-center justify-center rounded bg-indigo-500 shadow-inner">
          <Icon
            name="volume-up"
            class="h-5 w-5 text-white"
          />
        </div>

        <div class="flex flex-col">
          <span class="text-xs font-semibold text-gray-500">Average Volume</span>
          {{ averageVolume || '-' }}
        </div>
      </Link>
    </div>

    <div class="rounded border p-4 shadow-sm">
      <Link
        href="/rings"
        class="flex items-center"
      >
        <div class="mr-3 flex h-8 w-8 items-center justify-center rounded bg-indigo-500 shadow-inner">
          <Icon
            name="clock"
            class="h-5 w-5 text-white"
          />
        </div>

        <div class="flex flex-col">
          <span class="text-xs font-semibold text-gray-500">Last Ring</span>
          <time
            v-if="lastRing.date"
            :datetime="lastRing.date"
            :title="lastRing.date"
          >
            {{ lastRing.readable }}
          </time>
          <span v-else>-</span>
        </div>
      </Link>
    </div>
  </div>

  <h2 class="mb-8 text-2xl font-bold">
    Push Notifications
  </h2>

  <LoadingButton
    :loading="loading"
    class="mb-8 border p-4"
    @click="togglePush"
  >
    {{ isPushEnabled ? 'Disable' : 'Enable' }}
  </LoadingButton>
</template>
