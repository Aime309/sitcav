import {writable} from 'svelte/store'

const title = writable('');

title.subscribe(newTitle => {
  document.title = `${newTitle} ~ SITCAV`
})

export default title
