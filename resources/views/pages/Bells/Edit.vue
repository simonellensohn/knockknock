<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import TextInput from '@/views/components/TextInput.vue'
import LoadingButton from '@/views/components/LoadingButton.vue'
import SelectInput from '@/views/components/SelectInput.vue'

const props = defineProps({
  bell: Object,
})

const form = useForm({
  _method: 'put',
  name: props.bell.data.name,
  min_volume: props.bell.data.min_volume,
  max_volume: props.bell.data.max_volume,
  active: props.bell.data.active,
})

function update() {
  form.post(`/bells/${props.bell.data.id}`)
}

function destroy() {
  if (confirm('Are you sure you want to delete this bell?'))
    router.delete(`/bells/${props.bell.data.id}`)
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
        <SelectInput
          v-model="form.active"
          :error="form.errors.active"
          class="w-full pb-8 pr-6 lg:w-1/2"
          label="Active"
        >
          <option :value="true">
            Active
          </option>
          <option :value="false">
            Inactive
          </option>
        </SelectInput>
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
        <LoadingButton
          :loading="form.processing"
          class="btn-indigo ml-auto"
          type="submit"
        >
          Update Bell
        </LoadingButton>
      </div>
    </form>
  </div>
</template>
