import { ref, watch } from 'vue'
import { isArray, isEmpty } from 'lodash-es'

export default function useModel(prop, modelClass, { watchOptions = { deep: true, immediate: true }} = {}) {
  const asModel = ref(null)

  watch(prop, () => {
    if (isEmpty(asModel.value) || isArray(asModel.value)) {
      asModel.value = modelClass.make(prop.value)
    } else {
      asModel.value.fill(prop.value)
    }
  }, watchOptions)

  return {
    asModel
  }
}
