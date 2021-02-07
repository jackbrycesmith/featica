import { words, take } from 'lodash-es'

export function initials (value, maxInitials = 2) {
  const wordValues = take(words(value), maxInitials)

  let initialValues = ''
  wordValues.forEach(word => {
    initialValues += word.charAt(0)
  })

  return initialValues.toUpperCase()
}
