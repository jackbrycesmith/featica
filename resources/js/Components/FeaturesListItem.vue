<template>
  <li class="relative hover:bg-gray-50 hover:-mx-2.5 hover:px-2.5 hover:rounded-xl focus-within:bg-gray-50 focus-within:-mx-2.5 focus-within:px-2.5 focus-within:rounded-xl">
    <inertia-link
      :href="`/featica/feature/${feature.key}`"
      class="pl-4 pr-6 py-5 sm:py-6 sm:pl-6 lg:pl-8 xl:pl-6 focus:outline-none">
      <div class="flex items-center justify-between space-x-4">

        <div class="min-w-0 space-y-3">
          <div class="flex items-center space-x-3">

            <FeatureStateHint :state="feature.state" />

            <span class="block">
              <h2 class="text-sm font-medium">
                <div>
                  <span class="absolute inset-0" aria-hidden="true"></span>

                  <span class="sr-only">{{ feature.is_enabled ? 'Enabled' : 'Disabled' }}</span>
                </div>
              </h2>
            </span>
          </div>
          <div @click.prevent="copy(feature.key) && $refs.keyIcon.success()" class="relative group flex items-center space-x-2.5 cursor-pointer">
            <SuccessFlashSwitcher ref="keyIcon">
              <template #default>
                <HeroiconsMediumKey class="flex-shrink-0 w-5 h-5 text-gray-400 group-hover:text-gray-500" />
              </template>

              <template #success>
                <HeroiconsMediumClipboardCheck class="flex-shrink-0 w-5 h-5 text-green-500 group-hover:text-green-500" />
              </template>
            </SuccessFlashSwitcher>

            <span class="text-sm text-gray-500 group-hover:text-gray-900 font-medium truncate pr-1.5">
              {{ feature.key }}
            </span>
          </div>
        </div>
        <div class="">
          <HeroiconsSmallChevronRight class="h-5 w-5 text-gray-400" />
        </div>
      </div>
    </inertia-link>
  </li>
</template>

<script setup>
import { defineProps, ref } from 'vue'
import { useClipboard } from '@vueuse/core'
import HeroiconsMediumThumbUp from '@/svgs/heroicons/medium-thumb-up.svg'
import HeroiconsSmallChevronRight from '@/svgs/heroicons/small-chevron-right.svg'
import HeroiconsMediumThumbDown from '@/svgs/heroicons/medium-thumb-down.svg'
import HeroiconsMediumKey from '@/svgs/heroicons/medium-key.svg'
import HeroiconsMediumClipboardCheck from '@/svgs/heroicons/medium-clipboard-check.svg'
import SuccessFlashSwitcher from '@/Components/SuccessFlashSwitcher.vue'
import FeatureStateHint from '@/Components/FeatureStateHint.vue'

const keyIcon = ref(null)

const props = defineProps({ feature: { type: Object, required: true } })

const { copy } = useClipboard()
</script>
