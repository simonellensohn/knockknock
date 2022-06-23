<script setup lang="ts">
import { Link } from '@inertiajs/inertia-vue3'
import Icon from '@/views/components/Icon.vue'
import Dropdown from '@/views/components/Dropdown.vue'
import MainMenu from '@/views/components/MainMenu.vue'
import FlashMessages from '@/views/components/FlashMessages.vue'
import { inject } from 'vue'

const route = inject('route')

const props = defineProps({
  auth: Object,
})
</script>

<template>
  <div id="dropdown" />

  <div class="md:flex md:flex-col">
    <div class="md:flex md:h-screen md:flex-col">
      <div class="md:flex md:flex-shrink-0">
        <div
          class="flex items-center justify-between bg-indigo-900 px-6 py-4 md:w-56 md:flex-shrink-0 md:justify-center"
        >
          <Link
            class="mt-1"
            :href="route('dashboard')"
          >
            <span
              class="bg-gradient-to-br from-sky-500 to-cyan-400 bg-clip-text text-center text-4xl font-extrabold italic text-transparent"
            >Fat Flat</span>
          </Link>

          <Dropdown
            class="md:hidden"
            placement="bottom-end"
            aria-label="Toggle Navigation"
          >
            <template #default>
              <svg
                class="h-6 w-6 fill-white"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
              >
                <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
              </svg>
            </template>

            <template #dropdown>
              <div class="mt-2 rounded bg-indigo-800 px-8 py-4 shadow-lg">
                <MainMenu />
              </div>
            </template>
          </Dropdown>
        </div>

        <div class="md:text-md flex w-full items-center justify-end border-b bg-white p-4 text-sm md:px-12 md:py-0">
          <Dropdown
            class="mt-1"
            placement="bottom-end"
            aria-label="User Dropdown"
          >
            <template #default>
              <div class="group flex cursor-pointer select-none items-center">
                <div class="mr-1 whitespace-nowrap text-gray-700 focus:text-indigo-600 group-hover:text-indigo-600">
                  <span>{{ props.auth.user.first_name }}</span>
                  <span class="hidden md:inline">&nbsp;{{ props.auth.user.last_name }}</span>
                </div>

                <Icon
                  class="h-5 w-5 fill-gray-700 focus:fill-indigo-600 group-hover:fill-indigo-600"
                  name="cheveron-down"
                />
              </div>
            </template>

            <template #dropdown>
              <div class="mt-2 rounded bg-white py-2 text-sm shadow-xl">
                <Link
                  class="block px-6 py-2 hover:bg-indigo-500 hover:text-white"
                  :href="`/users/${props.auth.user.id}/edit`"
                >
                  My Profile
                </Link>
                <Link
                  class="block w-full px-6 py-2 text-left hover:bg-indigo-500 hover:text-white"
                  :href="route('logout')"
                  method="delete"
                  as="button"
                >
                  Logout
                </Link>
              </div>
            </template>
          </Dropdown>
        </div>
      </div>

      <div class="md:flex md:flex-grow md:overflow-hidden">
        <MainMenu class="hidden w-56 flex-shrink-0 overflow-y-auto bg-indigo-800 p-12 md:block" />

        <div
          class="px-4 py-8 md:flex-1 md:overflow-y-auto md:p-12"
          scroll-region
        >
          <FlashMessages />
          <slot />
        </div>
      </div>
    </div>
  </div>
</template>
