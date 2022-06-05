<template>
  <div class="space-y-4">
    <div>
      <Link class="group flex items-center py-3" :href="route('dashboard')">
        <icon name="dashboard" class="mr-2 h-4 w-4" :class="isUrl('') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Dashboard</div>
      </Link>
    </div>
    <div>
      <Link class="group flex items-center py-3" href="/users">
        <icon name="users" class="mr-2 h-4 w-4" :class="isUrl('users') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('users') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Users</div>
      </Link>
    </div>
    <div>
      <Link class="group flex items-center py-3" href="/bells">
        <icon name="bell" class="mr-2 h-6 w-6" :class="isUrl('bells') ? 'text-white' : 'text-indigo-400'" />
        <div :class="isUrl('bells') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Bells</div>
      </Link>
    </div>
  </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/inertia-vue3'
import Icon from '@/Shared/Icon'

const page = usePage()

function isUrl(...urls) {
  const currentUrl = page.url.value.substr(1)

  if (urls[0] === '') {
    return currentUrl === ''
  }

  return urls.filter((url) => currentUrl.startsWith(url)).length
}
</script>
