// biome-ignore lint/nursery/noNodejsModules:
import { spawn } from 'node:child_process'
import { defineConfig } from 'rollup'
import svelte from 'rollup-plugin-svelte'
import commonjs from '@rollup/plugin-commonjs'
import terser from '@rollup/plugin-terser'
import resolve from '@rollup/plugin-node-resolve'
import css from 'rollup-plugin-css-only'

const production = !process.env.ROLLUP_WATCH

function serve() {
  // eslint-disable-next-line init-declarations
  let server

  function toExit() {
    if (server) {
      server.kill(0)
    }
  }

  return {
    writeBundle() {
      if (server) {
        return
      }
      server = spawn('npm', ['run', 'start'], {
        shell: true,
        stdio: ['ignore', 'inherit', 'inherit']
      })

      process.on('SIGTERM', toExit)
      process.on('exit', toExit)
    }
  }
}

export default defineConfig({
  input: 'src/main.js',
  output: {
    file: 'app/build/bundle.js',
    format: 'iife',
    name: 'app',
    sourcemap: true
  },
  plugins: [
    svelte({
      compilerOptions: {
        // enable run-time checks when not in production
        dev: !production
      }
    }),
    // we'll extract any component CSS out into
    // a separate file - better for performance
    css({ output: 'bundle.css' }),

    // If you have external dependencies installed from
    // npm, you'll most likely need these plugins. In
    // some cases you'll need additional configuration -
    // consult the documentation for details:
    // https://github.com/rollup/plugins/tree/master/packages/commonjs
    resolve({
      browser: true,
      dedupe: ['svelte'],
      exportConditions: ['svelte']
    }),
    commonjs(),

    // In dev mode, call `npm run start` once
    // the bundle has been generated
    !production && serve(),

    // If we're building for production (npm run build
    // instead of npm run dev), minify
    production && terser()
  ],
  watch: {
    clearScreen: false
  }
})
