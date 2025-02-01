import esbuild from 'esbuild'
import { commonOptions, env } from './configuraciones.mjs'

await esbuild.build({
  ...commonOptions,
  define: {
    ...env,
    isDevelopment: 'false'
  },
  minify: true
})

console.info('Aplicación de Svelte compilada exitósamente')
