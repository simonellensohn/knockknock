<script setup>
import { v4 as uuid } from 'uuid'
import { ref, watch } from 'vue'

const props = defineProps({
  id: {
    type: String,
    default: () => `select-input-${uuid()}`,
  },
  error: String,
  label: String,
  modelValue: [String, Number, Boolean],
})

const emit = defineEmits(['update:modelValue'])

const selected = ref(props.modelValue)
const input = ref(null)

watch(selected, (selected) => {
  emit('update:modelValue', selected)
})
</script>

<script>
export default {
  inheritAttrs: false,
}
</script>

<template>
  <div :class="$attrs.class">
    <label
      v-if="label"
      class="form-label"
      :for="id"
    >{{ label }}:</label>

    <select
      :id="id"
      ref="input"
      v-model="selected"
      v-bind="{ ...$attrs, class: null }"
      class="form-select"
      :class="{ error: error }"
    >
      <slot />
    </select>

    <div
      v-if="error"
      class="form-error"
    >
      {{ error }}
    </div>
  </div>
</template>
