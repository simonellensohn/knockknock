<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import TextInput from '@/views/components/TextInput.vue'
import LoadingButton from '@/views/components/LoadingButton.vue'

const props = defineProps({
  auth: Object,
  user: Object,
  accessTokens: Array,
})

const form = useForm({
  _method: 'put',
  first_name: props.user.data.first_name,
  last_name: props.user.data.last_name,
  email: props.user.data.email,
  password: '',
})

function update() {
  form.post(`/users/${props.user.data.id}`, {
    onSuccess: () => form.reset('password', 'photo'),
  })
}

function destroy() {
  if (confirm('Are you sure you want to delete this user?'))
    router.delete(`/users/${props.user.data.id}`)
}

function deleteToken(token) {
  router.delete(`/users/${props.user.data.id}/access-tokens/${token.id}`)
}
</script>

<template>
  <Head :title="`${form.first_name} ${form.last_name}`" />

  <div class="mb-8 flex max-w-3xl justify-start">
    <h1 class="flex text-3xl font-bold">
      <Link
        class="text-indigo-400 hover:text-indigo-600"
        href="/users"
      >
        Users
      </Link>
      <span class="font-medium text-indigo-400">/</span>
      {{ form.first_name }} {{ form.last_name }}
    </h1>
  </div>

  <div class="mb-8 max-w-3xl overflow-hidden rounded-md bg-white shadow">
    <form @submit.prevent="update">
      <div class="-mb-8 -mr-6 flex flex-wrap p-8">
        <TextInput
          v-model="form.first_name"
          :error="form.errors.first_name"
          class="w-full pb-8 pr-6 lg:w-1/2"
          label="First name"
        />
        <TextInput
          v-model="form.last_name"
          :error="form.errors.last_name"
          class="w-full pb-8 pr-6 lg:w-1/2"
          label="Last name"
        />
        <TextInput
          v-model="form.email"
          :error="form.errors.email"
          class="w-full pb-8 pr-6 lg:w-1/2"
          label="Email"
        />
        <TextInput
          v-model="form.password"
          :error="form.errors.password"
          class="w-full pb-8 pr-6 lg:w-1/2"
          type="password"
          autocomplete="new-password"
          label="Password"
        />
      </div>

      <div class="flex items-center border-t border-gray-100 bg-gray-50 px-8 py-4">
        <button
          class="text-red-600 hover:underline"
          tabindex="-1"
          type="button"
          @click="destroy"
        >
          Delete User
        </button>
        <LoadingButton
          :loading="form.processing"
          class="btn-indigo ml-auto"
          type="submit"
        >
          Update User
        </LoadingButton>
      </div>
    </form>
  </div>

  <div v-if="props.user.data.id === props.auth.user.id">
    <h2 class="mb-8 text-2xl font-bold">
      Access Tokens
    </h2>

    <ul class="max-w-3xl space-y-4">
      <li
        v-for="token in props.accessTokens"
        :key="token.id"
        class="flex items-center justify-between rounded-md bg-white p-8 shadow"
      >
        <div>
          <span class="block font-bold">{{ token.name }}</span>
          <span class="text-sm">{{ token.last_used_at }} </span>
        </div>
        <button
          type="button"
          @click="deleteToken(token)"
        >
          Delete
        </button>
      </li>
    </ul>
  </div>
</template>
