<template>
  <div :class="$attrs.class">
    <label
      v-if="props.label"
      class="form-label"
      :for="props.id"
    >{{ props.label }}:</label>

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
    >
      {{ props.error }}
    </div>
  </div>
</template>

<script>
export default {
  inheritAttrs: false,
}
</script>

<script setup>
import { v4 as uuid } from 'uuid'
import { ref } from 'vue'

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
