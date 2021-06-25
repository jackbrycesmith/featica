<template>
  <div class="flex items-center space-x-2">
    <Icon
      icon="heroicons-solid:calendar"
      class="h-5 w-5 text-gray-400 flex-shrink-0"
    />
    <span class="text-gray-900 text-sm font-medium">
      {{ labelFormatted }} <time :datetime="dateString">{{ dateFormatted }}</time>
    </span>
  </div>
</template>

<script setup>
import { defineProps, computed } from 'vue'
import { format as formatDate, parseISO } from 'date-fns'
import { lowerCase, upperFirst } from 'lodash-es'

const props = defineProps({
  dateString: { type: String },
  label: { type: String, default: '' },
})

const labelFormatted = computed(() => upperFirst(lowerCase(props.label)))
const date = computed(() => parseISO(props.dateString))
const dateFormatted = computed(() => formatDate(date.value, 'MMM do, y'))

</script>
