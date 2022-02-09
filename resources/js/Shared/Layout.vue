<template>
  <div>
    <div id="dropdown" />
    <div class="md:flex md:flex-col">
      <div class="md:flex md:h-screen md:flex-col">
        <div class="md:flex md:flex-shrink-0">
          <div class="flex items-center justify-between bg-indigo-900 px-6 py-4 md:w-56 md:flex-shrink-0 md:justify-center">
            <Link class="mt-1" :href="$route('dashboard')">
              <span class="bg-gradient-to-br from-sky-500 to-cyan-400 bg-clip-text text-center text-4xl font-extrabold italic text-transparent">Fat Flat</span>
            </Link>
            <dropdown class="md:hidden" placement="bottom-end">
              <template #default>
                <svg class="h-6 w-6 fill-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" /></svg>
              </template>
              <template #dropdown>
                <div class="mt-2 rounded bg-indigo-800 px-8 py-4 shadow-lg">
                  <main-menu />
                </div>
              </template>
            </dropdown>
          </div>
          <div class="md:text-md flex w-full items-center justify-end border-b bg-white p-4 text-sm md:px-12 md:py-0">
            <dropdown class="mt-1" placement="bottom-end">
              <template #default>
                <div class="group flex cursor-pointer select-none items-center">
                  <div class="mr-1 whitespace-nowrap text-gray-700 focus:text-indigo-600 group-hover:text-indigo-600">
                    <span>{{ auth.user.first_name }}</span>
                    <span class="hidden md:inline">&nbsp;{{ auth.user.last_name }}</span>
                  </div>
                  <icon class="h-5 w-5 fill-gray-700 focus:fill-indigo-600 group-hover:fill-indigo-600" name="cheveron-down" />
                </div>
              </template>
              <template #dropdown>
                <div class="mt-2 rounded bg-white py-2 text-sm shadow-xl">
                  <Link class="block px-6 py-2 hover:bg-indigo-500 hover:text-white" :href="`/users/${auth.user.id}/edit`">My Profile</Link>
                  <Link class="block w-full px-6 py-2 text-left hover:bg-indigo-500 hover:text-white" :href="$route('logout')" method="delete" as="button">Logout</Link>
                </div>
              </template>
            </dropdown>
          </div>
        </div>
        <div class="md:flex md:flex-grow md:overflow-hidden">
          <main-menu class="hidden w-56 flex-shrink-0 overflow-y-auto bg-indigo-800 p-12 md:block" />
          <div class="px-4 py-8 md:flex-1 md:overflow-y-auto md:p-12" scroll-region>
            <flash-messages />
            <slot />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
import Icon from '@/Shared/Icon'
import Dropdown from '@/Shared/Dropdown'
import MainMenu from '@/Shared/MainMenu'
import FlashMessages from '@/Shared/FlashMessages'

export default {
  components: {
    Dropdown,
    FlashMessages,
    Icon,
    Link,
    MainMenu,
  },
  props: {
    auth: Object,
  },
}
</script>
