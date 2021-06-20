import { map, endsWith, trimEnd, some } from 'lodash-es'

export class NavItem {
  constructor (attributes = {
    label,
    href: null,
    icon: null,
    matchNested: false,
    nested: [],
  }) {
    Object.assign(this, attributes)
  }

  isActiveFor (pageUrl) {

    if (this.href.length >= 2 && endsWith(this.href, '/')) {
      this.href = trimEnd(this.href, '/')
    }

    let pathname = window.location.pathname
    if (pathname.length >= 2 && endsWith(pathname, '/')) {
      pathname = trimEnd(pathname, '/')
    }

    // Return early if matches exactly
    const matchesExact = pathname === this.href
    if (matchesExact) {
      return matchesExact
    }

    // Find navigation item & check if it must match the nested routes
    if (pathname.startsWith(this.href) && this.matchNested) {
      const nestedHrefs = map(this.nested ?? [], 'href') ?? []
      const matchesNested = some(nestedHrefs, function (value) {
        return pathname.startsWith(value)
      })

      return matchesNested
    }

    return pathname.startsWith(this.href)
  }
}
