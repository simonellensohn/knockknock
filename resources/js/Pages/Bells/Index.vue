<template>
  <div>
    <Head title="Bells" />
    <h1 class="mb-8 text-3xl font-bold">Bells</h1>
    <div class="flex items-center justify-end mb-6">
      <Link class="btn-indigo" href="/bells/create">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;Bell</span>
      </Link>
    </div>
    <div class="overflow-x-auto bg-white rounded-md shadow">
      <table class="w-full whitespace-nowrap">
        <tr class="font-bold text-left">
          <th class="px-6 pt-6 pb-4">Name</th>
          <th class="px-6 pt-6 pb-4">Threshold</th>
          <th class="px-6 pt-6 pb-4">Rings count</th>
        </tr>
        <tr v-for="bell in bells.data" :key="bell.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4 focus:text-indigo-500" :href="`/bells/${bell.id}/edit`">
              {{ bell.name }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/bells/${bell.id}/edit`" tabindex="-1">
              {{ bell.threshold }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/bells/${bell.id}/edit`" tabindex="-1">
              {{ bell.rings_count }}
            </Link>
          </td>
          <td class="w-px border-t">
            <Link class="flex items-center px-4" :href="`/bells/${bell.id}/edit`" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="bells.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No bells found.</td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Icon from '@/Shared/Icon'
import Layout from '@/Shared/Layout'

export default {
  components: {
    Head,
    Icon,
    Link,
  },
  layout: Layout,
  props: {
    bells: Object,
  },
}
</script>
