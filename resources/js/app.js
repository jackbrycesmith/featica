import { createApp, h } from 'vue'
import { InertiaProgress } from '@inertiajs/progress'
import { setup as twindSetup } from 'twind/shim'
import * as twindColors from 'twind/colors'
import { lineClamp } from '@twind/line-clamp'
import { aspectRatio } from '@twind/aspect-ratio'
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3'
import FeaticaLayout from '@/Layouts/FeaticaLayout'
import VueTippy from 'vue-tippy'
import 'tippy.js/dist/tippy.css'
import 'tippy.js/animations/shift-away.css'
import { Icon, loadIcons } from '@iconify/vue'

InertiaProgress.init({
  delay: 250,
  color: '#ffffffb5',
  includeCSS: true,
  showSpinner: false,
})

const el = document.getElementById('app')
const initialPage = JSON.parse(el.dataset.page)

const app = createApp({
  render: () => h(InertiaApp, {
    initialPage,
    resolveComponent: (name) => import(`@/Pages/${name}.vue`).then(({ default: page }) => {
      page.layout = page.layout === undefined ? FeaticaLayout : page.layout
      return page
    })
  }),
})

window.route = {
  get root_path () {
    return initialPage?.props?.featica_dashboard?.path ?? 'featica'
  },
  get home () {
    return `/${this.root_path}`
  },
  get feature () {
    return `/${this.root_path}/feature`
  },
  get model () {
    return `/${this.root_path}/model`
  },
  get model_users () {
    return `/${this.root_path}/model/users`
  },
  get model_teams () {
    return `/${this.root_path}/model/teams`
  },
}

app.config.globalProperties.$route = new Proxy(window.route, {})

app.use(InertiaPlugin)
app.use(VueTippy, {
  directive: 'tooltip',
  defaultProps: { allowHTML: true, animation: 'shift-away', inertia: true }
})

app.component('Icon', Icon)

app.mount(el)

twindSetup({
  theme: {
    extend: {
      fontFamily: (theme) => ({
        sans: ["Inter var", theme("fontFamily.sans")],
      }),
      colors: {
        ...twindColors,
      },
      keyframes: {
        wave: {
          '0%, 60%, 100%': { transform: 'rotate(0deg)' },
          '10%, 30%': { transform: 'rotate(14deg)' },
          '20%': { transform: 'rotate(-8deg)' },
          '40%': { transform: 'rotate(-4deg)' },
          '50%': { transform: 'rotate(10deg)' },
        }
      },
      animation: {
        wave: 'wave 2.5s infinite'
      }
    },
  },
  variants: {
    opacity: ['responsive', 'hover', 'focus', 'disabled'],
    extend: {
      margin: ['hover', 'focus-within'],
      padding: ['hover', 'focus-within'],
      borderRadius: ['hover', 'focus-within'],
      fontWeight: ['focus'],
    }
  },
  plugins: {
    aspect: aspectRatio,
    'line-clamp': lineClamp,
  }
})

// Preload icons
loadIcons([
  'heroicons-outline:key',
  'heroicons-outline:cube',
  'heroicons-outline:puzzle',
  'heroicons-outline:clipboard-check',
  'heroicons-solid:chevron-right',
  'heroicons-solid:calendar'
])
