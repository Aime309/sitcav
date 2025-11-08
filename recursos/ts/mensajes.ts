import Alpine from "alpinejs";

Alpine.data("mensajes", () => ({
  errores: [] as string[],
  exitos: [] as string[],

  init() {
    document.addEventListener("DOMContentLoaded", () => {
      this.errores = JSON.parse(document.body.dataset.errores || "[]");
      this.exitos = JSON.parse(document.body.dataset.exitos || "[]");
    });

    window.addEventListener("offline", () => {
      this.errores.push("Has perdido la conexión a la red");
    });

    window.addEventListener("online", () => {
      this.exitos.push("Conexión a la red restablecida");
    });
  },
}));
