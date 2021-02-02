import { onBeforeUnmount } from 'vue'

export default function useKeydown(keyCombos) {
  let onKeydown = (event) => {
    let keyCombo = keyCombos.find(keyCombo => keyCombo.key === event.key)
    if (keyCombo) {
      keyCombo?.fn(event)
    }
  }

  window.addEventListener('keydown', onKeydown)

  onBeforeUnmount(() => {
    window.removeEventListener('keydown', onKeydown)
  })
}
