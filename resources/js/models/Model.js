// https://gist.github.com/lorisleiva/93e66ba226ec53cc13c9e54d7f334f2c
import { getPayloadData } from '@/utils/data'

export default class Model {
    constructor (attributes = {}) {
        this.fill(attributes)
    }

    static make (attributes = {}) {
        return Array.isArray(attributes)
            ? attributes.map(nested => new this(nested))
            : new this(attributes)
    }

    fill (attributes = {}) {
        this.setAttributes(attributes)
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
