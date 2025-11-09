import Alpine from "alpinejs";

Alpine.data("mensajes", () => ({
  errores: [] as string[],
  exitos: [] as string[],
  advertencias: [] as string[],
  informaciones: [] as string[],

  init() {
    document.addEventListener("DOMContentLoaded", () => {
      this.errores = JSON.parse(document.body.dataset.errores || "[]");
      this.exitos = JSON.parse(document.body.dataset.exitos || "[]");

      this.advertencias = JSON.parse(
        document.body.dataset.advertencias || "[]",
      );
      this.informaciones = JSON.parse(
        document.body.dataset.informaciones || "[]",
      );
    });

    window.addEventListener("offline", () => {
      this.errores.push("Has perdido la conexión a la red");
    });

    window.addEventListener("online", () => {
      this.exitos.push("Conexión a la red restablecida");
    });
  },
}));
