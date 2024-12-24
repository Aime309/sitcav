import { writable } from 'svelte/store'

function createThemeStore() {
  const { set, subscribe } = writable('light', set => {
    const theme = localStorage.getItem('theme') ?? 'light'

    set(theme)
  })

  subscribe(theme => {
    document.documentElement.setAttribute('data-bs-theme', theme)
    localStorage.setItem('theme', theme)
  })

  return {
    subscribe,
    setDark() {
      set('dark')
    },
    setLight() {
      set('light')
    }
  }
}

const theme = createThemeStore()

export default theme
