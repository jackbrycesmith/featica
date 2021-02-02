import Model from './Model.js'

export default class Feature extends Model {

  get is_enabled () {
    return this.state === 'on'
  }

  get is_on_by_default () {
    return this.state === 'on'
  }

  get dates () {
    return this.meta?.dates ?? []
  }
}
