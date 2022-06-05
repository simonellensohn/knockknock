<script setup>
import { Inertia } from '@inertiajs/inertia'
import { Head, Link } from '@inertiajs/inertia-vue3'
import Icon from '@/views/components/Icon.vue'
import LoadingButton from '@/views/components/LoadingButton.vue'

const props = defineProps({
  bells: Object,
})

function toggleBells() {
  Inertia.post('/bells/toggle', {
    active: props.bells.data.some((bell) => !bell.active),
  })
}
</script>

<template layout="default">
  <Head title="Bells" />

  <h1 class="mb-8 text-3xl font-bold">Bells</h1>

  <div class="mb-6 flex items-center justify-end space-x-4">
    <LoadingButton
      class="btn-indigo"
      @click="toggleBells"
    >
      <span>Toggle bells</span>
    </LoadingButton>

    <Link
      class="btn-indigo"
      href="/bells/create"
    >
      <span>Create</span>
      <span class="hidden md:inline">&nbsp;Bell</span>
    </Link>
  </div>

  <div class="overflow-x-auto rounded-md bg-white shadow">
    <table class="w-full whitespace-nowrap">
      <tr class="text-left font-bold">
        <th class="px-6 pt-6 pb-4">Name</th>
        <th class="px-6 pt-6 pb-4">Threshold</th>
        <th class="px-6 pt-6 pb-4">Rings count</th>
        <th class="px-6 pt-6 pb-4">Active</th>
      </tr>

      <tr
        v-for="bell in props.bells.data"
        :key="bell.id"
        class="focus-within:bg-gray-100 hover:bg-gray-100"
      >
        <td class="border-t">
          <Link
            class="flex items-center px-6 py-4 focus:text-indigo-500"
            :href="`/bells/${bell.id}/edit`"
          >
            {{ bell.name }}
          </Link>
        </td>
        <td class="border-t">
          <Link
            class="flex items-center px-6 py-4"
            :href="`/bells/${bell.id}/edit`"
            tabindex="-1"
          >
            {{ bell.threshold }}
          </Link>
        </td>
        <td class="border-t">
          <Link
            class="flex items-center px-6 py-4"
            :href="`/bells/${bell.id}/edit`"
            tabindex="-1"
          >
            {{ bell.rings_count }}
          </Link>
        </td>
        <td class="border-t">
          <Link
            class="flex items-center px-6 py-4"
            :href="`/bells/${bell.id}/edit`"
            tabindex="-1"
          >
            {{ bell.active ? 'Active' : 'Inactive' }}
          </Link>
        </td>
        <td class="w-px border-t">
          <Link
            class="flex items-center px-4"
            :href="`/bells/${bell.id}/edit`"
            tabindex="-1"
          >
            <icon
              name="cheveron-right"
              class="block h-6 w-6 fill-gray-400"
            />
          </Link>
        </td>
      </tr>

      <tr v-if="props.bells.data.length === 0">
        <td
          class="border-t px-6 py-4"
          colspan="4"
        >
          No bells found.
        </td>
      </tr>
    </table>
  </div>
</template>
