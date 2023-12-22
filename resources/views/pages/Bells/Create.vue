<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import TextInput from '@/views/components/TextInput.vue'
import LoadingButton from '@/views/components/LoadingButton.vue'

const form = useForm({
  name: '',
  min_volume: '',
  max_volume: '',
})

function create() {
  form.post('/bells')
}
</script>

<template layout>
  <Head title="Create Bell" />

  <div class="mb-8 flex max-w-3xl justify-start">
    <h1 class="flex text-3xl font-bold">
      <Link
        class="text-indigo-400 hover:text-indigo-600"
        href="/bells"
      >
        Bells
      </Link>
      <span class="mx-1 font-medium text-indigo-400">/</span>
      New Bell
    </h1>
  </div>

  <div class="max-w-3xl overflow-hidden rounded-md bg-white shadow">
    <form @submit.prevent="create">
      <div class="-mb-8 -mr-6 flex flex-wrap p-8">
        <TextInput
          v-model="form.name"
          :error="form.errors.name"
          class="w-full pb-8 pr-6 lg:w-1/2"
          label="Name"
        />
        <TextInput
          v-model="form.min_volume"
          :error="form.errors.min_volume"
          class="w-full pb-8 pr-6 lg:w-1/2"
          label="Min Volume"
        />
        <TextInput
          v-model="form.max_volume"
          :error="form.errors.max_volume"
          class="w-full pb-8 pr-6 lg:w-1/2"
          label="Max Volume"
        />
      </div>
      <div class="flex items-center border-t border-gray-100 bg-gray-50 px-8 py-4">
        <LoadingButton
          :loading="form.processing"
          class="btn-indigo ml-auto"
          type="submit"
        >
          Create Bell
        </LoadingButton>
      </div>
    </form>
  </div>
</template>
