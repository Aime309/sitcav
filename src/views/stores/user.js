// @ts-nocheck

import { readable } from 'svelte/store'

/** @type {{id?: number, idCard?: number, isLogged: boolean}} */
const initialUser = {
  id: undefined,
  idCard: undefined,

  get isLogged() {
    return this.id !== undefined
  }
}

const user = readable(initialUser, set => {
  fetch('./api/perfil')
    .then(response => response.json())
    .then(user =>
      set({
        ...initialUser,
        ...user
      })
    )
})

export default user
