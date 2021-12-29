<template>
  <div>
    <Head title="Create Bell" />
    <div class="flex justify-start max-w-3xl mb-8">
      <h1 class="text-3xl font-bold">
        <Link class="text-indigo-400 hover:text-indigo-600" href="/bells">Bells</Link>
        <span class="mx-1 font-medium text-indigo-400">/</span>
        New Bell
      </h1>
    </div>
    <div class="max-w-3xl overflow-hidden bg-white rounded-md shadow">
      <form @submit.prevent="create">
        <div class="flex flex-wrap p-8 -mb-8 -mr-6">
          <text-input v-model="form.name" :error="form.errors.name" class="w-full pb-8 pr-6 lg:w-1/2" label="Name" />
          <text-input v-model="form.threshold" :error="form.errors.threshold" class="w-full pb-8 pr-6 lg:w-1/2" label="Threshold" />
        </div>
        <div class="flex items-center px-8 py-4 border-t border-gray-100 bg-gray-50">
          <loading-button :loading="form.processing" class="ml-auto btn-indigo" type="submit">Create Bell</loading-button>
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
  props: {
    bell: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name: '',
        threshold: '',
      }),
    }
  },
  methods: {
    create() {
      this.form.post('/bells')
    },
  },
}
</script>
