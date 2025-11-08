import Offcanvas from "bootstrap/js/dist/offcanvas";

document.addEventListener("DOMContentLoaded", () => {
  for (const elemento of document.querySelectorAll(".offcanvas")) {
    new Offcanvas(elemento);
  }
});

window.bootstrap = {
  ...(window.bootstrap || {}),
  Offcanvas,
};
