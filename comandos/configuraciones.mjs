import { copyFileSync, existsSync, readFileSync, writeFileSync } from "node:fs";
import { parse } from "dotenv";
import esbuildSvelte from "esbuild-svelte";
import { sveltePreprocess } from "svelte-preprocess";

/** @type {import('esbuild').BuildOptions} */
export const commonOptions = {
  bundle: true,
  entryPoints: ["src/index.ts"],
  format: "esm",
  loader: {
    ".module.css": "local-css",
    ".ttf": "copy",
    ".woff": "copy",
    ".woff2": "copy",
    ".jpg": "copy",
  },
  outfile: "app/build/bundle.js",
  target: ["es2018"],
  plugins: [
    esbuildSvelte({
      preprocess: sveltePreprocess(),
    }),
  ],
  conditions: ["main"],
};

if (!existsSync(".env")) {
  copyFileSync(".env.dist", ".env");
}

const env = parse(readFileSync(".env"));
let declarations = "";

for (const variable in env) {
  env[variable] = `"${env[variable]}"`;
  declarations += `declare const ${variable}: string\n`;
}

writeFileSync("src/env.d.ts", declarations);

export { env };
