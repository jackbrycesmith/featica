<template>
  <li class="bg-white">
    <div class="relative px-6 py-5 flex items-center space-x-3 hover:bg-gray-50 focus-within:bg-gray-50 focus-within:ring-1 focus-within:ring-inset focus-within:ring-gray-200">
      <div class="flex-shrink-0">
        <ImageFallback class="bg-green-400">
          {{ modelId }}
        </ImageFallback>
      </div>
      <div class="flex-1 min-w-0">
        <inertia-link
          :href="href"
          class="focus:outline-none"
        >
          <span
            class="absolute inset-0"
            aria-hidden="true"
          />
          <p class="text-sm font-medium text-gray-900">
            {{ modelTitle }}
          </p>
          <p class="text-sm text-gray-500 truncate">
            {{ modelSubtitle }}
          </p>
        </inertia-link>
      </div>
    </div>
  </li>
</template>

<script>
import ImageFallback from '@/Components/ImageFallback'

export default {
  name: "ModelsListItem",
  components: {
    ImageFallback
  },
  props: {
    type: {
      type: String,
      required: true
    },
    model: {
      type: Object,
      required: true
    }
  },
  computed: {
    modelTitle () {
      if (this.type === 'users') {
        return this.model?.['name']
      }

      return this.model?.['name'] ?? this.model?.['email']
    },
    modelSubtitle () {
      if (this.type === 'users') {
        return this.model?.['email']
      }

      return ''
    },
    modelId () {
      return this.model.id
    },
    href () {
      return `${this.$route.model}\\${this.type}\\${this.modelId}`
    }
  }
}
</script>
