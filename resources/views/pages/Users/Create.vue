<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import TextInput from '@/views/components/TextInput.vue'
import LoadingButton from '@/views/components/LoadingButton.vue'

const form = useForm({
  first_name: '',
  last_name: '',
  email: '',
  password: '',
})

function store() {
  form.post('/users')
}
</script>

<template>
  <Head title="Create User" />

  <h1 class="mb-8 flex text-3xl font-bold">
    <Link
      class="text-indigo-400 hover:text-indigo-600"
      href="/users"
    >
      Users
    </Link>
    <span class="font-medium text-indigo-400">/</span>
    Create
  </h1>

  <div class="max-w-3xl overflow-hidden rounded-md bg-white shadow">
    <form @submit.prevent="store">
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
      <div class="flex items-center justify-end border-t border-gray-100 bg-gray-50 px-8 py-4">
        <LoadingButton
          :loading="form.processing"
          class="btn-indigo"
          type="submit"
        >
          Create User
        </LoadingButton>
      </div>
    </form>
  </div>
</template>
