import Collapse from "bootstrap/js/dist/collapse";

document.addEventListener("DOMContentLoaded", () => {
  for (const element of document.querySelectorAll(".collapse")) {
    new Collapse(element, { toggle: false });
  }
});

// @ts-ignore
window.bootstrap = {
  ...(window.bootstrap || {}),
  Collapse,
};
