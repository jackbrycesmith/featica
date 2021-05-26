<template>
  <ul class="relative z-0 divide-y divide-gray-200 border-b border-gray-200">
    <FeaturesListItem
      v-for="feature in features"
      :key="feature.key"
      :feature="feature"
    />
  </ul>
</template>

<script>
import FeaturesListItem from '@/Components/FeaturesListItem'

export default {
  name: 'FeaturesList',
  components: {
    FeaturesListItem
  },
  props: {
    features: {
      type: Array,
      default: () => []
    }
  },
  data () {
    return {

    }
  }
}
</script>
