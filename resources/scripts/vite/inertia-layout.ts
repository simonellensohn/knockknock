import type { Plugin } from 'vite'

const PLUGIN_NAME = 'vite:inertia:layout'
const TEMPLATE_LAYOUT_REGEX = /<template +layout(?: *= *['"]([-_\w/]+)['"] *)?>/

/**
 * A basic Vite plugin that adds a <template layout="name"> syntax to Vite SFCs.
 * It must be used before the Vue plugin.
 */
export default (layouts = '@/views/layouts/'): Plugin => ({
  name: PLUGIN_NAME,
  transform: (code: string) => {
    const isTypeScript = /lang=['"]ts['"]/.test(code)

    return code.replace(
      TEMPLATE_LAYOUT_REGEX,
      (_, layoutName: string | null) => `
			<script${isTypeScript ? ' lang="ts"' : ''}>
			import layout from '${layouts}${layoutName ? layoutName[0].toUpperCase() + layoutName.slice(1) : 'Default'}.vue'
			export default { layout }
			</script>
			<template>
		`,
    )
  },
})
