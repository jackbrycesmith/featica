import { computed, reactive } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import { NavItem } from '@/Utils/Navigation.js'
import { map } from 'lodash-es'

export default function useNavigation() {
  const page = reactive(usePage())

  const navigationItems = computed(() => {
    const items = page.props.featica_dashboard.nav_items
    const mappedItems = map(items, item => {
      return new NavItem({
        label: item.label,
        href: item.route,
        icon: item.icon
      })
    })

    return mappedItems
  })

  return {
    navigationItems
  }
}
