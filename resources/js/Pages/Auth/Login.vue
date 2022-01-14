<template>
  <Head title="Login" />
  <div class="flex items-center justify-center min-h-screen p-6 bg-indigo-800">
    <div class="w-full max-w-md">
      <h1 class="italic font-extrabold text-center text-transparent text-7xl bg-clip-text bg-gradient-to-br from-sky-500 to-cyan-400">Fat Flat</h1>
      <form class="mt-8 overflow-hidden bg-white rounded-lg shadow-xl" @submit.prevent="login">
        <div class="px-10 py-12">
          <h1 class="text-3xl font-bold text-center">Welcome Back!</h1>
          <div class="w-24 mx-auto mt-6 border-b-2" />
          <text-input v-model="form.email" :error="form.errors.email" class="mt-10" label="Email" type="email" autofocus autocapitalize="off" />
          <text-input v-model="form.password" :error="form.errors.password" class="mt-6" label="Password" type="password" />
          <label class="flex items-center mt-6 select-none" for="remember">
            <input id="remember" v-model="form.remember" class="mr-1" type="checkbox" />
            <span class="text-sm">Remember Me</span>
          </label>
        </div>
        <div class="flex px-10 py-4 bg-gray-100 border-t border-gray-100">
          <loading-button :loading="form.processing" class="ml-auto btn-indigo" type="submit">Login</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head } from '@inertiajs/inertia-vue3'
import TextInput from '@/Shared/TextInput'
import LoadingButton from '@/Shared/LoadingButton'

export default {
  components: {
    Head,
    LoadingButton,
    TextInput,
  },
  data() {
    return {
      form: this.$inertia.form({
        email: '',
        password: '',
        remember: false,
      }),
    }
  },
  methods: {
    login() {
      this.form.post(this.$route('login'))
    },
  },
}
</script>
