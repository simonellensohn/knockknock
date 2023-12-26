import Layout from '@layouts/Default.vue'

export function importPageComponent(name: string) {
  const pages = import.meta.glob('../../views/pages/**/*.vue', { eager: true })
  const page = pages[`../../views/pages/${name.replaceAll('.', '/')}.vue`]

  page.default.layout = page.default.layout || Layout

  return page
}
