<template>
  <div>
    <Head title="Rings" />
    <h1 class="mb-8 text-3xl font-bold">Rings</h1>
    <div class="overflow-x-auto bg-white rounded-md shadow">
      <table class="w-full whitespace-nowrap">
        <tr class="font-bold text-left">
          <th class="px-6 pt-6 pb-4">Time</th>
          <th class="px-6 pt-6 pb-4">Volume</th>
          <th class="px-6 pt-6 pb-4">Events</th>
          <th class="px-6 pt-6 pb-4">Bell</th>
        </tr>
        <tr v-for="ring in rings.data" :key="ring.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
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
        <tr v-if="rings.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No recent rings found.</td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import { Head } from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/Layout'

export default {
  components: {
    Head,
  },
  layout: Layout,
  props: {
    rings: Object,
  },
}
</script>
