<template>
  <button ref="el" type="button" @click="show = true">
    <slot />

    <teleport v-if="show" to="#dropdown">
      <div>
        <div style="position: fixed; top: 0; right: 0; left: 0; bottom: 0; z-index: 99998; background: black; opacity: 0.2" @click="show = false" />
        <div ref="dropdown" style="position: absolute; z-index: 99999" @click.stop="show = !props.autoClose">
          <slot name="dropdown" />
        </div>
      </div>
    </teleport>
  </button>
</template>

<script setup>
import { createPopper } from '@popperjs/core'
import { nextTick, onMounted, ref, watch } from 'vue'

const props = defineProps({
  placement: {
    type: String,
    default: 'bottom-end',
  },
  autoClose: {
    type: Boolean,
    default: true,
  },
})

const el = ref(null)
const show = ref(false)
const popper = ref(null)
const dropdown = ref(null)

watch(show, (show) => {
  if (show) {
    nextTick(() => {
      popper.value = createPopper(el.value, dropdown.value, {
        placement: props.placement,
        modifiers: [
          {
            name: 'preventOverflow',
            options: {
              altBoundary: true,
            },
          },
        ],
      })
    })
  } else if (popper.value) {
    setTimeout(() => popper.value.destroy(), 100)
  }
})

onMounted(() => {
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      show.value = false
    }
  })
})
</script>
