<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import { inject } from 'vue'
import GuestLayout from '@layouts/Guest.vue'
import TextInput from '@/views/components/TextInput.vue'
import LoadingButton from '@/views/components/LoadingButton.vue'

defineOptions({ layout: GuestLayout })

const route = inject('route')

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

function login() {
  form.post(route('login'))
}
</script>

<template layout="guest">
  <Head title="Login" />

  <div class="flex min-h-screen items-center justify-center bg-indigo-800 p-6">
    <div class="w-full max-w-md">
      <h1
        class="bg-gradient-to-br from-sky-500 to-cyan-400 bg-clip-text text-center text-7xl font-extrabold italic text-transparent"
      >
        Fat Flat
      </h1>

      <form
        class="mt-8 overflow-hidden rounded-lg bg-white shadow-xl"
        @submit.prevent="login"
      >
        <div class="px-10 py-12">
          <h1 class="text-center text-3xl font-bold">
            Welcome Back!
          </h1>
          <div class="mx-auto mt-6 w-24 border-b-2" />
          <TextInput
            v-model="form.email"
            :error="form.errors.email"
            class="mt-10"
            label="Email"
            type="email"
            autofocus
            autocapitalize="off"
          />
          <TextInput
            v-model="form.password"
            :error="form.errors.password"
            class="mt-6"
            label="Password"
            type="password"
          />
          <label
            class="mt-6 flex select-none items-center"
            for="remember"
          >
            <input
              id="remember"
              v-model="form.remember"
              class="mr-1"
              type="checkbox"
            >
            <span class="text-sm">Remember Me</span>
          </label>
        </div>

        <div class="flex border-t border-gray-100 bg-gray-100 px-10 py-4">
          <LoadingButton
            :loading="form.processing"
            class="btn-indigo ml-auto"
            type="submit"
          >
            Login
          </LoadingButton>
        </div>
      </form>
    </div>
  </div>
</template>
