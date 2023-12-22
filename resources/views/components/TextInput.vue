<script setup lang="ts">
import { v4 as uuid } from 'uuid'
import { ref } from 'vue'

defineOptions({
  inheritAttrs: false,
})

const props = defineProps({
  id: {
    type: String,
    default: () => `text-input-${uuid()}`,
  },
  type: {
    type: String,
    default: 'text',
  },
  error: String,
  label: String,
  modelValue: [String, Number],
})

const emit = defineEmits(['update:modelValue'])

const input = ref(null)
</script>

<template>
  <div :class="$attrs.class">
    <label
      v-if="props.label"
      class="form-label"
      :for="props.id"
      v-text="`${props.label}:`"
    />

    <input
      :id="props.id"
      ref="input"
      v-bind="{ ...$attrs, class: null }"
      class="form-input"
      :class="{ error: props.error }"
      :type="props.type"
      :value="props.modelValue"
      @input="emit('update:modelValue', $event.target.value)"
    >

    <div
      v-if="props.error"
      class="form-error"
      v-text="props.error"
    />
  </div>
</template>
