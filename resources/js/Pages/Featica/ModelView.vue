<template>
  <main
    class="flex-1 relative overflow-y-auto focus:outline-none"
    tabindex="-1"
  >
    <div class="py-8 xl:py-10">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 xl:max-w-5xl xl:grid xl:grid-cols-3">
        <div class="xl:col-span-2 xl:pr-8 xl:border-r xl:border-gray-200">
          <div>
            <div>
              <div class="md:flex md:items-center md:justify-between md:space-x-4 xl:border-b xl:pb-6">
                <div>
                  <h1 class="text-2xl font-bold text-gray-900">
                    {{ modelTitle }}
                  </h1>
                  <p class="mt-2 text-sm text-gray-500">
                    Model Type: {{ type }}
                  </p>
                </div>
                <div class="mt-4 flex space-x-3 md:mt-0" />
              </div>
              <aside class="mt-8 xl:hidden">
                <h2 class="sr-only">
                  Details
                </h2>
                <div class="space-y-5">
                  <DateIconLabel
                    v-if="model__.created_at"
                    label="created_on"
                    :date-string="model__.created_at"
                  />
                </div>
                <div class="mt-6 border-t border-b border-gray-200 py-6 space-y-8" />
              </aside>
              <div class="py-3 xl:pt-6 xl:pb-0">
                <h2 class="sr-only">
                  Feature Flags
                </h2>

                <ul>
                  <ModelFeatureFlagItem
                    v-for="feature in $page.props.features"
                    :key="feature.key"
                    v-model:model="model__.feature_flags"
                    :feature="feature"
                    :type="type"
                    @changed="handleFeatureFlagChange"
                  />
                </ul>
              </div>
            </div>
          </div>
        </div>
        <aside class="hidden xl:block xl:pl-8">
          <h2 class="sr-only">
            Details
          </h2>
          <div class="space-y-5">
            <div class="flex items-center space-x-2" />

            <DateIconLabel
              v-if="model__.created_at"
              label="created_on"
              :date-string="model__.created_at"
            />
          </div>
        </aside>
      </div>
    </div>
  </main>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import { debounce, cloneDeep } from 'lodash-es'
import { useTitle } from '@vueuse/core'
import { defineProps, ref, computed, watch } from 'vue'
import DateIconLabel from '@/Components/DateIconLabel.vue'
import HeroiconsSmallCalendar from '@/svgs/heroicons/small-calendar.svg'
import ModelFeatureFlagItem from '@/Components/ModelFeatureFlagItem.vue'

const props = defineProps({
  type: {
    type: String,
  },
  model: {}
})

const model__ = ref({})

const modelTitle = computed(() => {
  if (props.type === 'users') {
    return props.model?.['name']
  }

  return props.model?.['name'] ?? props.model?.['email']
})

useTitle(`${modelTitle.value} | featica`)

watch(() => props.model, () => {
  model__.value = cloneDeep(props.model)
}, { deep: true, immediate: true })

const handleFeatureFlagChange = () => updateModelFeatureFlagsRequest()

const updateModelFeatureFlagsRequest = debounce(() => {
  Inertia.put(`${route.model}/${props.type}/${props.model.id}`, {
    feature_flags: model__.value.feature_flags
  })
}, 500)
</script>
