<template>
  <div>
    <div class="relative sm:bg-red-50 pb-32 overflow-hidden">
      <div
        class="absolute flex justify-center inset-x-0 left-1/2 transform -translate-x-1/2 w-full overflow-hidden lg:inset-y-0"
        aria-hidden="true"
      >
        <div class="flex-grow bg-red-500 bg-opacity-95" />
        <svg
          class="flex-shrink-0"
          width="1750"
          height="308"
          viewBox="0 0 1750 308"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            opacity=".75"
            d="M1465.84 308L16.816 0H1750v308h-284.16z"
            fill="currentColor"
            class="text-green-500"
          />
          <path
            opacity=".95"
            d="M1733.19 0L284.161 308H0V0h1733.19z"
            fill="currentColor"
            class="text-red-500"
          />
        </svg>
        <div class="flex-grow bg-green-500 bg-opacity-75" />
      </div>
      <header class="relative py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h1 class="text-3xl font-bold text-white truncate select-none">
            <FeaticaLogo class="inline-block h-10" />
            <span class="text-lg align-middle ml-2">for {{ appName }}</span>
          </h1>
        </div>
      </header>

      <div class="custom-shape-divider-bottom-1609865803">
        <svg
          data-name="Layer 1"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 1200 120"
          preserveAspectRatio="none"
        >
          <path
            d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"
            opacity=".25"
            class="shape-fill"
          />
          <path
            d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z"
            opacity=".5"
            class="shape-fill"
          />
          <path
            d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"
            class="shape-fill"
          />
        </svg>
      </div>
    </div>

    <main class="relative -mt-32">
      <div class="max-w-screen-xl mx-auto pb-6 px-4 sm:px-6 lg:pb-16 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
          <div
            class="divide-y divide-gray-200 lg:grid lg:grid-cols-12 lg:divide-y-0 lg:divide-x"
            style="min-height: 80vh;"
          >
            <aside class="py-6 lg:col-span-3">
              <nav>
                <!-- Features navigation item -->
                <inertia-link
                  :href="featureNavItem.href"
                  :class="isNavItemActive(featureNavItem.href)
                    ? 'bg-teal-50 border-teal-500 text-teal-700 hover:bg-teal-50 hover:text-teal-700'
                    : 'border-transparent text-gray-900 hover:bg-gray-50 hover:text-gray-900'
                  "
                  class="group border-l-4 px-3 py-2 flex items-center text-sm font-medium"
                >
                  <component
                    :is="featureNavItem.iconComponent"
                    :class="isNavItemActive(featureNavItem.href) ? 'text-teal-500 group-hover:text-teal-500' : 'text-gray-400 group-hover:text-gray-500'"
                    class="flex-shrink-0 -ml-1 mr-3 h-6 w-6"
                  />

                  <span class="truncate">
                    {{ featureNavItem.name }}
                  </span>
                </inertia-link>

                <!-- Navigation separator -->
                <div
                  title="Models that have feature flags…"
                  class="py-5 relative"
                >
                  <div
                    class="absolute inset-0 flex items-center"
                    aria-hidden="true"
                  >
                    <div class="border-gray-200 border-t w-full" />
                  </div>
                  <div class="flex justify-center relative">
                    <span class="bg-white pl-3 pr-2 pr-3 text-gray-500 text-sm">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        class="h-5 inline-block text-yellow-400 w-5"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                        />
                      </svg>
                    </span>
                  </div>
                </div>

                <!-- Model navigation items -->
                <inertia-link
                  v-for="navItem in filteredNavItems"
                  :key="navItem.name"
                  :href="navItem.href"
                  :class="isNavItemActive(navItem.href)
                    ? 'bg-teal-50 border-teal-500 text-teal-700 hover:bg-teal-50 hover:text-teal-700'
                    : 'border-transparent text-gray-900 hover:bg-gray-50 hover:text-gray-900'
                  "
                  class="group border-l-4 px-3 py-2 flex items-center text-sm font-medium"
                >
                  <component
                    :is="navItem.iconComponent"
                    :class="isNavItemActive(navItem.href) ? 'text-teal-500 group-hover:text-teal-500' : 'text-gray-400 group-hover:text-gray-500'"
                    class="flex-shrink-0 -ml-1 mr-3 h-6 w-6"
                  />

                  <span class="truncate">
                    {{ navItem.name }}
                  </span>
                </inertia-link>
              </nav>
            </aside>

            <div class="lg:col-span-9">
              <slot />
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
// import FeaticaLogo from '@/svgs/featica-logo-mark-plain-black.svg'
// import FeaticaLogo from '@/svgs/featica-logo.svg'
// import FeaticaLogo from '@/svgs/featica-logo-with-mark-again.svg'
import FeaticaLogo from '@/svgs/featica-logo-with-filled-bg-mark-dots-ryg.svg'
// import FeaticaMark from '@/svgs/featica-circle-mark.svg'
import HeroiconsMediumPuzzle from '@/svgs/heroicons/medium-puzzle.svg'
import HeroiconsMediumUser from '@/svgs/heroicons/medium-user.svg'
import HeroiconsMediumUserGroup from '@/svgs/heroicons/medium-user-group.svg'
import { find, filter, map, endsWith, trimEnd, some } from 'lodash-es'

export default {
  components: {
    FeaticaLogo,
    // FeaticaMark
  },
  metaInfo: () => ({
    htmlAttrs: {
      class: ['h-full bg-gray-100']
    }
  }),
  computed: {
    appName () {
      return this.$page.props?.featica_dashboard?.app_name
    },
    navItems () {
      return [
        {
          name: 'Features',
          href: this.$route.home,
          iconComponent: HeroiconsMediumPuzzle,
          matchNested: true,
          nested: [
            {
              name: 'Feature',
              href: this.$route.feature
            }
          ]
        },
        {
          name: 'Users',
          href: this.$route.model_users,
          iconComponent: HeroiconsMediumUser
        },
        {
          name: 'Teams',
          href: this.$route.model_teams,
          iconComponent: HeroiconsMediumUserGroup
        },
      ]
    },
    featureNavItem () {
      return find(this.navItems, { name: 'Features' })
    },
    filteredNavItems ()  {
      return filter(this.navItems, (item) => item.name !== 'Features')
    }
  },
  methods: {
    isNavItemActive (href) {
      if (href.length >= 2 && endsWith(href, '/')) {
        href = trimEnd(href, '/')
      }

      let pathname = window.location.pathname
      if (pathname.length >= 2 && endsWith(pathname, '/')) {
        pathname = trimEnd(pathname, '/')
      }

      // Return early if matches exactly
      const matchesExact = pathname === href
      if (matchesExact) {
        return matchesExact
      }

      // Find navigation item & check if it must match the nested routes
      const navItem = find(this.navItems, { href })
      const matchNested = navItem?.matchNested ?? false

      if (pathname.startsWith(href) && matchNested) {
        const nestedHrefs = map(navItem?.nested ?? [], 'href') ?? []
        const matchesNested = some(nestedHrefs, function (value) {
          return pathname.startsWith(value)
        })

        return matchesNested
      }

      return pathname.startsWith(href)
    }
  },
}
</script>

<style>
html {
  scroll-behavior: smooth;
}

.custom-shape-divider-bottom-1609865803 {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    overflow: hidden;
    line-height: 0;
    transform: rotate(180deg);
}

.custom-shape-divider-bottom-1609865803 svg {
    position: relative;
    display: block;
    width: calc(100% + 1.3px);
    height: 57px;
}

.custom-shape-divider-bottom-1609865803 .shape-fill {
    fill: #FFFFFF;
}
</style>
