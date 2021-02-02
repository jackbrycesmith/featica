<template>
  <slot
    v-if="!isSuccess"
    name="default"/>

  <slot
    v-if="isSuccess"
    name="success"/>
</template>

<script>
export default {
  props: {
    showSuccessLengthMs: { type: Number, default: 1000 },
    toBlurAfterSuccessEl: { }
  },
  data () {
    return {
      isSuccess: false
    }
  },
  methods: {
    success () {
      if (this.isSuccess) return
      this.isSuccess = true

      setTimeout(() => {
        this.isSuccess = false
        this.toBlurAfterSuccessEl?.blur()
      }, this.showSuccessLengthMs)
    }
  }
}
</script>
