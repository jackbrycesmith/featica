<template>
  <ul class="relative divide-y divide-gray-200">

    <ModelsListItem
      v-for="model in models"
      :key="model.id"
      :type="type"
      :model="model"
    />

  </ul>
</template>

<script>
import ModelsListItem from '@/Components/ModelsListItem'

export default {
  name: "ModelsTable",
  components: {
    ModelsListItem
  },
  props: {
    type: {
      type: String
    },
    models: {
      type: Array
    }
  }
}
</script>

<style scoped>

</style>
