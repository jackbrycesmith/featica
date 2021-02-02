import { createApp, h } from 'vue'
import { InertiaProgress } from '@inertiajs/progress'
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3'
import FeaticaLayout from '@/Layouts/FeaticaLayout'

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
app.mount(el)
