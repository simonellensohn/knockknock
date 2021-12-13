<template>
  <div>
    <Head title="Dashboard" />

    <h1 class="mb-8 text-3xl font-bold">Dashboard</h1>

    <ul>
      <li v-for="ring in rings" :key="ring.id">{{ ring.created_at }}</li>
    </ul>
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
    rings: { type: Array },
  }
}
</script>
