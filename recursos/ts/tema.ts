import Alpine from "alpinejs";

Alpine.data("tema", () => ({
  tema: undefined as "light" | "dark" | undefined,
  tema_colores: document.documentElement.dataset.colorTheme || "Blue_Theme",
  direccion: document.documentElement.dataset.dir || "ltr",
  layout: document.documentElement.dataset.layout || "vertical",

  get temaInverso() {
    return this.tema === "dark" ? "light" : "dark";
  },

  init() {
    document.addEventListener("DOMContentLoaded", () => {
      this.tema =
        document.documentElement.dataset.bsTheme ||
        matchMedia("(prefers-color-scheme: dark)").matches
          ? "dark"
          : "light";
    });

    matchMedia("(prefers-color-scheme: dark)").addEventListener(
      "change",
      (evento) => {
        this.tema = evento.matches ? "dark" : "light";
      },
    );

    this.$watch("tema", (nuevoTema) => {
      document.documentElement.dataset.bsTheme = nuevoTema;

      fetch("./api/ajustes/tema", {
        method: "post",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          tema: nuevoTema,
        }),
      });
    });

    this.$watch("tema_colores", (nuevoTemaColores) => {
      fetch("./api/ajustes/tema", {
        method: "post",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          tema_colores: nuevoTemaColores,
        }),
      });
    });

    this.$watch("direccion", (nuevaDireccion) => {
      fetch("./api/ajustes/tema", {
        method: "post",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          direccion: nuevaDireccion,
        }),
      });
    });

    this.$watch("layout", (nuevoLayout) => {
      fetch("./api/ajustes/tema", {
        method: "post",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          layout: nuevoLayout,
        }),
      });
    });
  },
}));
