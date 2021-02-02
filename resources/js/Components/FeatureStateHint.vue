<template>
  <div class="inline-flex items-center">
    <p
      :class="{ 'bg-green-100 text-green-800': feature.is_on_by_default, 'bg-red-100 text-red-600': !feature.is_on_by_default }"
      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
      {{ feature.is_on_by_default ? 'ON' : 'OFF' }}
    </p>
    <span class="text-xs text-gray-500 ml-1.5">by default</span>
  </div>
</template>

<script>
export default {
  name: "FeatureStateHint",
  props: {
    feature: { type: Object, required: true }
  }
}
</script>
