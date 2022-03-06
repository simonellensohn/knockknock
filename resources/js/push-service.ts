export function registerServiceWorker(callback) {
  if (!('serviceWorker' in navigator)) {
    debug('Service workers aren\'t supported in this browser.')

    return
  }

  navigator.serviceWorker.register('/sw.js').then(() => callback(initialiseServiceWorker()))
}

function initialiseServiceWorker() {
  if (!('showNotification' in ServiceWorkerRegistration.prototype)) {
    debug('Notifications aren\'t supported.')

    return
  }

  if (Notification.permission === 'denied') {
    debug('The user has blocked notifications.')

    return
  }

  if (!('PushManager' in window)) {
    debug('Push messaging isn\'t supported.')

    return
  }

  return navigator.serviceWorker.ready.then((registration) => {
    return registration.pushManager
      .getSubscription()
      .catch((e) => {
        debug('Error during getSubscription()', e)
      })
  })
}

interface SubscribeCallbacks {
  onSuccess?: () => void,
  onError?: () => void,
}

interface PushSubscriptionOptions {
  userVisibleOnly: boolean,
  applicationServerKey?: Uint8Array,
}

function subscribe(vapidPublicKey: string = null, callbacks: SubscribeCallbacks) {
  navigator.serviceWorker.ready.then((registration) => {
    const options: PushSubscriptionOptions = { userVisibleOnly: true }

    if (vapidPublicKey) {
      options.applicationServerKey = urlBase64ToUint8Array(vapidPublicKey)
    }

    registration.pushManager
      .subscribe(options)
      .then((subscription) => {
        callbacks.onSuccess()

        this.isPushEnabled = true
        this.pushButtonDisabled = false
        this.updateSupdateSubscriptionubscription(subscription)
      })
      .catch((e) => {
        if (Notification.permission === 'denied') {
          debug('Permission for Notifications was denied')
          this.pushButtonDisabled = true
        } else {
          debug('Unable to subscribe to push.', e)
          this.pushButtonDisabled = false
        }
      })
  })
}

function unsubscribe(callbacks) {
  navigator.serviceWorker.ready.then((registration) => {
    registration.pushManager
      .getSubscription()
      .then((subscription) => {
        if (!subscription) {
          callbacks.onNotFound()

          this.isPushEnabled = false
          this.pushButtonDisabled = false

          return
        }

        subscription
          .unsubscribe()
          .then(() => {
            callbacks.onSuccess(subscription)

            this.deleteSubscription(subscription)
            this.isPushEnabled = false
            this.pushButtonDisabled = false
          })
          .catch((e) => {
            debug('Unsubscription error: ', e)
            this.pushButtonDisabled = false

            callbacks.onError(e)
          })
      })
      .catch((e) => {
        debug('Error thrown while unsubscribing.', e)
      })
  })
}

export function updateSubscription(subscription, callback) {
  const key = subscription.getKey('p256dh')
  const token = subscription.getKey('auth')
  const contentEncoding = (PushManager.supportedContentEncodings || ['aesgcm'])[0]
  const data = {
    endpoint: subscription.endpoint,
    publicKey: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
    authToken: token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null,
    contentEncoding,
  }

  callback(data)
}

/**
 * https://github.com/Minishlink/physbook/blob/02a0d5d7ca0d5d2cc6d308a3a9b81244c63b3f14/app/Resources/public/js/app.js#L177
 *
 * @param  {String} base64String
 * @return {Uint8Array}
 */
function urlBase64ToUint8Array(base64String) {
  const padding = '='.repeat((4 - (base64String.length % 4)) % 4)
  const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/')
  const rawData = window.atob(base64)
  const outputArray = new Uint8Array(rawData.length)

  for (let i = 0; i < rawData.length; ++i) {
    outputArray[i] = rawData.charCodeAt(i)
  }

  return outputArray
}

function debug(message: string): void {
  if (process.env.NODE_ENV === 'production') {
    return
  }

  console.log(message)
}
