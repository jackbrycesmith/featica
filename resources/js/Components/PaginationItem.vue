<template>
  <div
    v-if="link.url === null"
    :class="{ 'ml-auto': isNext, 'cursor-not-allowed': isPrevious || isNext }"
    :title="title"
    class="mr-1.5 mb-1 px-4 py-3 text-sm border rounded-full focus:outline-none focus:ring-2 focus:ring-gray-200 text-gray-400 focus:font-semibold text-center select-none">
    {{ label }}
  </div>

  <inertia-link
    v-else
    class="pagination-item mr-1.5 mb-1 px-4 py-3 text-sm border rounded-full hover:bg-gray-50 focus:outline-none focus:ring-2 focus:font-semibold focus:ring-gray-200 text-center select-none"
    :class="{ 'bg-gray-50': link.active, 'ml-auto': isNext }"
    :title="title"
    :href="link.url">
    {{ label }}
  </inertia-link>
</template>

<script>

export default {
  name: 'PaginationItem',
  props: {
    link: {
      type: Object
    },
    isNext: {
      type: Boolean,
      default: false
    },
    isPrevious: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    label () {
      if (this.isPrevious) {
        return '«'
      }

      if (this.isNext) {
        return '»'
      }

      return this.link.label
    },
    title () {
      if (this.isPrevious) {
        return 'Previous'
      }

      if (this.isNext) {
        return 'Next'
      }
    }
  }
}
</script>
