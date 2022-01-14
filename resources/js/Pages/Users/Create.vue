<template>
  <div>
    <Head title="Create User" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/users">Users</Link>
      <span class="font-medium text-indigo-400">/</span> Create
    </h1>
    <div class="max-w-3xl overflow-hidden bg-white rounded-md shadow">
      <form @submit.prevent="store">
        <div class="flex flex-wrap p-8 -mb-8 -mr-6">
          <text-input v-model="form.first_name" :error="form.errors.first_name" class="w-full pb-8 pr-6 lg:w-1/2" label="First name" />
          <text-input v-model="form.last_name" :error="form.errors.last_name" class="w-full pb-8 pr-6 lg:w-1/2" label="Last name" />
          <text-input v-model="form.email" :error="form.errors.email" class="w-full pb-8 pr-6 lg:w-1/2" label="Email" />
          <text-input v-model="form.password" :error="form.errors.password" class="w-full pb-8 pr-6 lg:w-1/2" type="password" autocomplete="new-password" label="Password" />
        </div>
        <div class="flex items-center justify-end px-8 py-4 border-t border-gray-100 bg-gray-50">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Create User</loading-button>
        </div>
      </form>
    </div>
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
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        first_name: '',
        last_name: '',
        email: '',
        password: '',
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/users')
    },
  },
}
</script>
