<template>
  <div class="py-6 px-4 sm:p-6 lg:pb-8">
    <div>
      <h2 class="text-lg leading-6 font-medium text-gray-900">
        {{ modelListTitle }}
      </h2>
      <p class="mt-1 text-sm text-gray-500">
        That can have feature flags.
      </p>

      <div class="max-w-xs w-full lg:max-w-md">
        <label
          for="search"
          class="sr-only"
        >Search</label>
        <div class="relative text-gray-500 focus-within:text-gray-600">
          <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center">
            <svg
              class="h-5 w-5"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 20 20"
              fill="currentColor"
              aria-hidden="true"
            >
              <path
                fill-rule="evenodd"
                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                clip-rule="evenodd"
              />
            </svg>
          </div>
          <input
            id="search"
            ref="searchInput"
            v-model="form.search"
            class="block w-full text-gray-500 bg-white bg-opacity-80 py-2 pl-7 pr-3 border border-transparent rounded-md leading-5 focus:text-gray-900 placeholder-gray400 focus:outline-none focus:bg-opacity-100 focus:border-transparent focus:placeholder-gray-500 focus:ring-0 sm:text-sm"
            placeholder="Search…"
            type="search"
            name="search"
          >
        </div>
      </div>
    </div>

    <ModelsList
      :models="models.data"
      :type="type"
      class="mt-5"
    />

    <Pagination :links="models.links" />
  </div>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import useKeydown from '@/Composables/useKeydown.js'
import { upperFirst, throttle, pickBy } from 'lodash-es'
import { useTitle } from '@vueuse/core'
import { defineProps, computed, ref, watch } from 'vue'
import Pagination from '@/Components/Pagination.vue'
import ModelsList from '@/Components/ModelsList.vue'

const props = defineProps({
  type: {
    type: String,
    default: 'Models'
  },
  models: {
    type: Object
  },
  filters: {
    type: Object,
    default: () => {}
  }
})

const searchInput = ref(null)

const modelListTitle = computed(() => upperFirst(props.type))

const form = ref({
  search: props.filters?.search
})

const title = computed(() => {
  return `${modelListTitle.value} | featica`
})

useTitle(title)

watch(form, throttle(() => {
  let query = pickBy(form.value)
  let url = new URL(window.location.href)
  if (query.search) {
    url.searchParams.set('search', query.search)
  } else {
    url.searchParams.delete('search')
  }

  Inertia.visit(
    url,
    {
      replace: true,
      preserveState: true,
      only: ['models']
    }
  )
}, 300), { deep: true })

useKeydown([
  { key: '/', fn: handleSearchInputFocus }
])

function handleSearchInputFocus (event) {
  if (document.activeElement !== searchInput.value) {
    event.preventDefault()
    searchInput.value?.focus()
  }
}

</script>
