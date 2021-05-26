<template>
  <div v-if="shouldDisplay">
    <h2 class="text-sm font-medium text-gray-500">
      Owner
    </h2>

    <li class="flex justify-start mt-1.5">
      <div class="flex items-center space-x-3">
        <div class="flex-shrink-0 flex h-7 w-7 text-xs rounded-full font-medium bg-yellow-200 text-gray-900 justify-center items-center">
          <span v-text="initials(name ?? defaultOwner)" />
        </div>

        <div class="text-sm font-medium text-gray-900">
          {{ name ?? defaultOwner }}
        </div>
      </div>
    </li>
  </div>
</template>

<script setup>
import { isEmpty } from 'lodash-es'
import { defineProps, computed } from 'vue'
import { initials } from '@/Utils/String.js'
import { usePage } from '@inertiajs/inertia-vue3'

const props = defineProps({
  name: { type: String },
})

const page = usePage()
const defaultOwner = computed(() => {
  return page?.props.value.featica_dashboard?.default_feature_owner
})
const shouldDisplay = computed(() => !isEmpty(props.name) || !isEmpty(defaultOwner.value))
</script>
