<template>
  <div class="py-6 px-4 sm:p-6 lg:pb-8">
    <div>
      <h2 class="text-lg leading-6 font-medium text-gray-900">
        Features
      </h2>
      <p class="mt-1 text-sm text-gray-500">
        The features you have configured for your app.
      </p>
    </div>

    <FeaturesList
      :features="features__"
      class="mt-5"
    />
  </div>
</template>

<script setup>
import { defineProps, ref, watch } from 'vue'
import { useTitle } from '@vueuse/core'
import { getPayloadData } from '@/Utils/Data.js'
import FeaticaLogo from '@/svgs/featica-logo.svg'
import FeaturesList from '@/Components/FeaturesList.vue'
import Feature from '@/Models/Feature.js'
useTitle('Features | featica')

const props = defineProps({
  features: {
    type: Array,
    default: () => []
  }
})

// const modelFeatures = useModel(Feature, props.features)

const features__ = ref([])
watch(() => props.features, () => {
  features__.value = Feature.make(getPayloadData(props.features))
}, { deep: true, immediate: true })

</script>
