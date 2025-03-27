import esbuild from "esbuild";
import { commonOptions, env } from "./configuraciones.mjs";

const context = await esbuild.context({
  ...commonOptions,
  define: {
    ...env,
    isDevelopment: "true",
  },
  sourcemap: "external",
});

await context.watch();
console.info("Aplicación de Svelte compilada exitósamente...");
console.info("Vigilando cambios en los archivos...");
console.info("Ve al navegador en http://localhost:8080");
