<script setup>
const props = defineProps({
  loading: Boolean,
})
</script>

<template>
  <button :disabled="props.loading" class="flex items-center">
    <div v-if="props.loading" class="btn-spinner mr-2" />
    <slot />
  </button>
</template>
