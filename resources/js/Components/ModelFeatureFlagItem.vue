<template>
  <li>
    <div class="relative py-5 flex items-center">
      <div class="flex flex-col space-y-2">
        <div
          class="relative group flex items-center space-x-2.5 cursor-pointer"
          @click.prevent="copy(feature.key) && keyIcon.success()"
        >
          <SuccessFlashSwitcher ref="keyIcon">
            <template #default>
              <Icon
                icon="heroicons-outline:key"
                class="flex-shrink-0 w-5 h-5 text-gray-400 group-hover:text-gray-500"
              />
            </template>

            <template #success>
              <Icon
                icon="heroicons-outline:clipboard-check"
                class="flex-shrink-0 w-5 h-5 text-green-500 group-hover:text-green-500"
              />
            </template>
          </SuccessFlashSwitcher>

          <span class="text-sm text-gray-500 group-hover:text-gray-900 font-medium truncate pr-1.5">
            {{ feature.key }}
          </span>
        </div>

        <FeatureStateHint :state="feature.state" />
      </div>

      <SwitchGroup
        as="div"
        class="flex items-center space-x-4 flex-1 justify-end"
      >
        <SwitchLabel class="sr-only">
          Toggle feature flag availability for this model
        </SwitchLabel>

        <Switch
          v-slot="{ checked }"
          v-model="toggleState"
          as="button"
          :class-name="resolveSwitchClass"
        >
          <span
            class="inline-block w-5 h-5 transition duration-200 ease-in-out transform bg-white rounded-full"
            :class="{ 'translate-x-5': checked, 'translate-x-0': !checked }"
          />
        </Switch>
      </SwitchGroup>
    </div>
  </li>
</template>

<script>
import { computed, defineProps, defineEmit, ref } from 'vue'
import { useClipboard } from '@vueuse/core'
import { SwitchGroup, Switch, SwitchLabel } from '@headlessui/vue'
import SuccessFlashSwitcher from '@/Components/SuccessFlashSwitcher.vue'
import FeatureStateHint from '@/Components/FeatureStateHint.vue'

export default {
  components: { SwitchGroup, Switch, SwitchLabel, SuccessFlashSwitcher, FeatureStateHint },
  props: {
    feature: { type: Object, required: true },
    modelValue: { type: Object, default: null },
    type: { type: String, required: true },
  },
  emits: ['update:modelValue', 'changed'],
  setup (props, { emit }) {
    const { copy } = useClipboard()
    const keyIcon = ref(null)

    const toggleState = computed({
      get: () => props.modelValue?.[`${props.feature.key}`] === 'on',
      set: val => {
        const modelKey = props.feature.key
        let existingModel = (props.modelValue === 'null') ? {} : props.modelValue
        const updateModel = { ...existingModel, [modelKey]: val ? 'on' : 'off' }
        emit('update:modelValue', updateModel)
        emit('changed')
      }
    })

    function classNames(...classes) {
      return classes.filter(Boolean).join(' ')
    }

    function resolveSwitchClass({ checked }) {
      return classNames(
        'relative inline-flex flex-shrink-0 h-6 transition-colors duration-200 ease-in-out border-2 border-transparent rounded-full cursor-pointer w-11 focus:outline-none focus:shadow-outline',
        checked ? 'bg-green-500' : 'bg-gray-200'
      )
    }

    return { classNames, copy, keyIcon, resolveSwitchClass, toggleState }
  },
}
</script>
