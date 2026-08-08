import Alpine from 'alpinejs'
import persist from '@alpinejs/persist'
import './'

window.Alpine = Alpine

Alpine.plugin(persist)
Alpine.start()
