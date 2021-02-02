<template>
  <div class="h-10 w-10 rounded-full inline-flex items-center justify-center select-none">
    <span class="px-1.5 text-sm font-mono truncate">
      <slot />
    </span>
  </div>
</template>

<script>
export default {
  name: "ImageFallback",
  props: {
    url: {
      type: String,
      required: false
    }
  }
}
</script>

<style scoped>

</style>
