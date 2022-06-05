<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import TextInput from '@/Shared/TextInput'
import LoadingButton from '@/Shared/LoadingButton'
import SelectInput from '@/Shared/SelectInput'
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
  bell: Object,
})

const form = useForm({
  _method: 'put',
  name: props.bell.data.name,
  threshold: props.bell.data.threshold,
  active: props.bell.data.active,
})

function update() {
  form.post(`/bells/${props.bell.data.id}`)
}

function destroy() {
  if (confirm('Are you sure you want to delete this bell?')) {
    Inertia.delete(`/bells/${props.bell.data.id}`)
  }
}
</script>

<template>
  <Head :title="form.name" />

  <div class="mb-8 flex max-w-3xl justify-start">
    <h1 class="flex text-3xl font-bold">
      <Link
        class="text-indigo-400 hover:text-indigo-600"
        href="/bells"
      >
        Bells
      </Link>
      <span class="mx-1 font-medium text-indigo-400">/</span>
      {{ form.name }}
    </h1>
  </div>

  <div class="max-w-3xl overflow-hidden rounded-md bg-white shadow">
    <form @submit.prevent="update">
      <div class="-mb-8 -mr-6 flex flex-wrap p-8">
        <text-input
          v-model="form.name"
          :error="form.errors.name"
          class="w-full pb-8 pr-6 lg:w-1/2"
          label="Name"
        />
        <text-input
          v-model="form.threshold"
          :error="form.errors.threshold"
          class="w-full pb-8 pr-6 lg:w-1/2"
          label="Threshold"
        />
        <select-input
          v-model="form.active"
          :error="form.errors.active"
          class="w-full pb-8 pr-6 lg:w-1/2"
          label="Active"
        >
          <option :value="true">Active</option>
          <option :value="false">Inactive</option>
        </select-input>
      </div>

      <div class="flex items-center border-t border-gray-100 bg-gray-50 px-8 py-4">
        <button
          class="text-red-600 hover:underline"
          tabindex="-1"
          type="button"
          @click="destroy"
        >
          Delete Bell
        </button>
        <loading-button
          :loading="form.processing"
          class="btn-indigo ml-auto"
          type="submit"
        >
          Update Bell
        </loading-button>
      </div>
    </form>
  </div>
</template>
