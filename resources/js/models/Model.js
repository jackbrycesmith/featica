// https://gist.github.com/lorisleiva/93e66ba226ec53cc13c9e54d7f334f2c
import { getPayloadData } from '@/Utils/Data.js'
import { cloneDeep } from 'lodash-es'

export default class Model {
  constructor (attributes = {}) {
    this.fill(attributes)
  }

  static make (attributes = {}) {
    const data = getPayloadData(attributes)

    return Array.isArray(data)
      ? data.map(nested => new this(nested))
      : new this(data)
  }

  fill (attributes = {}) {
    this.setAttributes(cloneDeep(attributes))
    this.wrapRelationships()
    return this
  }

  setAttributes (attributes) {
    Object.assign(this, getPayloadData(attributes))
  }

  getAttributes () {
    return { ...this }
  }

  clone () {
    return this.constructor.make({ ...this.getAttributes() })
  }

  wrapRelationships () {
    let attributes = this.getAttributes() || {}
    let relationships = this.getRelationships() || {}

    Object.keys(relationships).forEach(key => {
      /* eslint-disable no-prototype-builtins */
      if (attributes.hasOwnProperty(key) && attributes[key]) {
        attributes[key] = relationships[key].make(attributes[key])
      }
    })

    this.setAttributes(attributes)
  }

  getRelationships () {
    return {}
  }
}
