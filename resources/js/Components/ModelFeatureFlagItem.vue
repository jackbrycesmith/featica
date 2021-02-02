<template>
  <li>
    <div class="relative py-5 flex items-center">
      <span>{{ feature.key }}</span>
      <SwitchGroup as="div" class="flex items-center space-x-4 flex-1 justify-end">
        <SwitchLabel class="sr-only">Toggle feature flag availability for this model</SwitchLabel>

        <Switch as="button" v-model="toggleState" :className="resolveSwitchClass" v-slot="{ checked }">
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
import { SwitchGroup, Switch, SwitchLabel } from '@headlessui/vue'

function classNames(...classes) {
  return classes.filter(Boolean).join(' ')
}

export default {
  name: "ModelFeatureFlagItem",
  components: {
    SwitchLabel,
    SwitchGroup,
    Switch
  },
  props: {
    feature: {
      type: Object
    },
    model: {
      // type: Object
    },
    type: {
      type: String
    },
  },
  emits: ['update:modelValue', 'update:model', 'changed'],
  computed: {
    toggleState: {
      get () {
        return this.model?.[`${this.feature.key}`] === 'on'
      },
      set (val) {
        // set(this.model, `feature_flags.${this.feature.key}`, val ? 'on' : 'off')
        const modelKey = this.feature.key
        let existingModel = (this.model === 'null') ? {} : this.model
        const updateModel = { ...existingModel, [modelKey]: val ? 'on' : 'off' }
        this.$emit('update:model', updateModel)
        this.$emit('changed')
      }
    }
  },
  // data () {
  //   return {
  //     toggleState: false
  //   }
  // },
  methods: {
    resolveSwitchClass ({ checked }) {
        return classNames(
          'relative inline-flex flex-shrink-0 h-6 transition-colors duration-200 ease-in-out border-2 border-transparent rounded-full cursor-pointer w-11 focus:outline-none focus:shadow-outline',
          checked ? 'bg-green-500' : 'bg-gray-200'
        )
    }
  }
}
</script>
