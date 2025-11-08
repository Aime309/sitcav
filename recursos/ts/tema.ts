import Alpine from "alpinejs";

Alpine.data("tema", () => ({
  tema: matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light",
  tema_colores: document.documentElement.dataset.colorTheme || "Blue_Theme",

  get temaInverso() {
    return this.tema === "dark" ? "light" : "dark";
  },

  init() {
    matchMedia("(prefers-color-scheme: dark)").addEventListener(
      "change",
      (evento) => {
        this.tema = evento.matches ? "dark" : "light";
      },
    );

    this.$watch("tema", (nuevoTema) => {
      fetch("./api/ajustes/tema", {
        method: "post",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          tema: nuevoTema,
          tema_colores: this.tema_colores,
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
          tema: this.tema,
          tema_colores: nuevoTemaColores,
        }),
      });
    });
  },
}));
