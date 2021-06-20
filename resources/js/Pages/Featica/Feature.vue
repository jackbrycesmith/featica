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
                <div class="max-w-full space-y-2.5">
                  <h1 class="text-xl sm:text-3xl font-bold text-gray-900 truncate">
                    {{ feature?.meta?.['name'] ?? startCase(feature.key) }}
                  </h1>
                  <h2
                    class="text-lg sm:text-xl flex items-center space-x-2.5 font-medium text-gray-500 group cursor-copy"
                    @click="copy(feature.key) && keyIcon.success()"
                  >
                    <SuccessFlashSwitcher ref="keyIcon">
                      <template #default>
                        <HeroiconsMediumKey class="flex-shrink-0 w-6 h-6 group-hover:text-gray-600" />
                      </template>

                      <template #success>
                        <HeroiconsMediumClipboardCheck class="flex-shrink-0 w-6 h-6 text-green-500" />
                      </template>
                    </SuccessFlashSwitcher>

                    <span class="pr-1.5 group-hover:text-gray-600">{{ feature.key }}</span>
                  </h2>
                  <p class="text-sm text-gray-500">
                    <FeatureStateHint :state="feature.state" />
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
                    v-for="(date, key) in modelFeature.dates"
                    :key="key"
                    :label="key"
                    :date-string="date"
                  />
                </div>
                <div
                  :class="{ 'border-t mt-6 pt-6': size(modelFeature.dates) > 0 }"
                  class="border-b border-gray-200 pb-6 space-y-8"
                >
                  <FeatureOwner :name="modelFeature.owner_name" />
                </div>
              </aside>
              <div class="py-3 xl:pt-6 xl:pb-0" />
            </div>
          </div>
        </div>
        <aside class="hidden xl:block xl:pl-8">
          <h2 class="sr-only">
            Details
          </h2>
          <div class="space-y-5">
            <DateIconLabel
              v-for="(date, key) in modelFeature.dates"
              :key="key"
              :label="key"
              :date-string="date"
            />
          </div>
          <div
            :class="{ 'border-t mt-6 py-6': size(modelFeature.dates) > 0 }"
            class="border-gray-200 space-y-8"
          >
            <FeatureOwner :name="modelFeature.owner_name" />
          </div>
        </aside>
      </div>
    </div>
  </main>
</template>

<script setup>
import { defineProps, ref, toRef  , computed } from 'vue'
import { useClipboard, useTitle } from '@vueuse/core'
import { parseISO, format as formatDate } from 'date-fns'
import { startCase, lowerCase, upperFirst, size } from 'lodash-es'
import useModel from '@/Composables/useModel.js'
import FeaticaLogo from '@/svgs/featica-logo.svg'
import HeroiconsSmallCalendar from '@/svgs/heroicons/small-calendar.svg'
import HeroiconsMediumKey from '@/svgs/heroicons/medium-key.svg'
import HeroiconsMediumClipboardCheck from '@/svgs/heroicons/medium-clipboard-check.svg'
import FeaturesList from '@/Components/FeaturesList.vue'
import DateIconLabel from '@/Components/DateIconLabel.vue'
import FeatureOwner from '@/Components/FeatureOwner.vue'
import Feature from '@/Models/Feature.js'
import FeatureStateHint from '@/Components/FeatureStateHint.vue'
import SuccessFlashSwitcher from '@/Components/SuccessFlashSwitcher.vue'

const props = defineProps({
  feature: { type: Object, required: true }
})
const { asModel: modelFeature } = useModel(toRef(props, 'feature'), Feature)

const { copy } = useClipboard()

const keyIcon = ref(null)

const title = computed(() => {
  return `${modelFeature.value?.key} | featica`
})

useTitle(title)
</script>
