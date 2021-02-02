<template>
  <div class="mt-6 -mb-1 flex flex-wrap">
    <PaginationItem
      v-for="(link, key) in links"
      :key="key"
      :is-previous="key === 0"
      :is-next="key === (links.length - 1)"
      :link="link"
      class="pagination-item" />
  </div>
</template>

<script setup>
import { defineProps } from 'vue'
import PaginationItem from '@/Components/PaginationItem.vue'

defineProps({ links: Array })
</script>

<style scoped>
.pagination-item {
  min-width: 45px;
  min-height: 45px;
}
</style>
