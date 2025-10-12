import { defineConfig } from "@rsbuild/core";

export default defineConfig({
  performance: {
    chunkSplit: {
      strategy: "all-in-one",
    },
  },
  source: {
    entry: {
      visitantes: "./recursos/visitantes.ts",
      autenticados: "./recursos/autenticados.ts",
    },
  },
  dev: {
    writeToDisk: true,
    hmr: false,
    assetPrefix: "./",
  },
  output: {
    assetPrefix: "./",
    distPath: {
      root: "./recursos/compilados",
      js: "",
      css: "",
      font: "",
      svg: "",
    },
    filenameHash: false,
  },
  plugins: [
  ],
});
