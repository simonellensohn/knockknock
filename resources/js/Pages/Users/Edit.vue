<template>
  <Head :title="`${form.first_name} ${form.last_name}`" />

  <div class="mb-8 flex max-w-3xl justify-start">
    <h1 class="text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/users">Users</Link>
      <span class="font-medium text-indigo-400">/</span>
      {{ form.first_name }} {{ form.last_name }}
    </h1>
  </div>

  <div class="mb-8 max-w-3xl overflow-hidden rounded-md bg-white shadow">
    <form @submit.prevent="update">
      <div class="-mb-8 -mr-6 flex flex-wrap p-8">
        <text-input v-model="form.first_name" :error="form.errors.first_name" class="w-full pb-8 pr-6 lg:w-1/2" label="First name" />
        <text-input v-model="form.last_name" :error="form.errors.last_name" class="w-full pb-8 pr-6 lg:w-1/2" label="Last name" />
        <text-input v-model="form.email" :error="form.errors.email" class="w-full pb-8 pr-6 lg:w-1/2" label="Email" />
        <text-input v-model="form.password" :error="form.errors.password" class="w-full pb-8 pr-6 lg:w-1/2" type="password" autocomplete="new-password" label="Password" />
      </div>
      <div class="flex items-center border-t border-gray-100 bg-gray-50 px-8 py-4">
        <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update User</loading-button>
      </div>
    </form>
  </div>

  <div v-if="user.data.id === auth.user.id">
    <h2 class="mb-8 text-2xl font-bold">Access Tokens</h2>

    <ul class="max-w-3xl space-y-4">
      <li v-for="token in accessTokens" :key="token.id" class="flex items-center justify-between rounded-md bg-white p-8 shadow">
        <div>
          <span class="block font-bold">{{ token.name }}</span>
          <span class="text-sm">{{ token.last_used_at }} </span>
        </div>
        <button type="button" @click="deleteToken(token)">Delete</button>
      </li>
    </ul>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import LoadingButton from '@/Shared/LoadingButton'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    TextInput,
  },
  layout: Layout,
  props: {
    auth: Object,
    user: Object,
    accessTokens: Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        _method: 'put',
        first_name: this.user.data.first_name,
        last_name: this.user.data.last_name,
        email: this.user.data.email,
        password: '',
      }),
    }
  },
  methods: {
    update() {
      this.form.post(`/users/${this.user.data.id}`, {
        onSuccess: () => this.form.reset('password', 'photo'),
      })
    },
    destroy() {
      if (confirm('Are you sure you want to delete this user?')) {
        this.$inertia.delete(`/users/${this.user.data.id}`)
      }
    },
    deleteToken(token) {
      this.$inertia.delete(`/users/${this.user.data.id}/access-tokens/${token.id}`)
    },
  },
}
</script>
