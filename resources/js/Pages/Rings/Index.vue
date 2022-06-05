<script setup>
import { Head } from '@inertiajs/inertia-vue3'

const props = defineProps({
  rings: Object,
})
</script>

<template>
  <Head title="Rings" />

  <h1 class="mb-8 text-3xl font-bold">Rings</h1>

  <div class="overflow-x-auto rounded-md bg-white shadow">
    <table class="w-full whitespace-nowrap">
      <tr class="text-left font-bold">
        <th class="px-6 pt-6 pb-4">Time</th>
        <th class="px-6 pt-6 pb-4">Volume</th>
        <th class="px-6 pt-6 pb-4">Events</th>
        <th class="px-6 pt-6 pb-4">Bell</th>
      </tr>

      <tr v-for="ring in props.rings.data" :key="ring.id" class="focus-within:bg-gray-100 hover:bg-gray-100">
        <td class="border-t">
          <span class="flex items-center px-6 py-4 focus:text-indigo-500" :title="ring.created_at.date">
            {{ ring.created_at.diff }}
          </span>
        </td>
        <td class="border-t">
          <span class="flex items-center px-6 py-4" tabindex="-1">
            {{ ring.volume }}
          </span>
        </td>
        <td class="border-t">
          <pre class="px-6 py-4" tabindex="-1" v-text="ring.events" />
        </td>
        <td class="border-t">
          <span class="flex items-center px-6 py-4" tabindex="-1">
            {{ ring.bell.name }}
          </span>
        </td>
      </tr>

      <tr v-if="props.rings.data.length === 0">
        <td class="border-t px-6 py-4" colspan="4">No recent rings found.</td>
      </tr>
    </table>
  </div>
</template>
