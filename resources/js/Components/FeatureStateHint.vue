<template>
  <div class="inline-flex items-center">
    <p
      :class="{ 'bg-green-100 text-green-800': isOn, 'bg-red-100 text-red-600': !isOn }"
      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
    >
      {{ isOn ? 'ON' : 'OFF' }}
    </p>
    <span class="text-xs text-gray-500 ml-1.5">by default</span>
  </div>
</template>

<script name="FeatureStateHint" setup>
import { computed, defineProps } from 'vue'

const props = defineProps({ state: String })

const isOn = computed(() => props.state === 'on')
</script>
