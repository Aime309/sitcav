import Tooltip from "bootstrap/js/dist/tooltip";

document.addEventListener("DOMContentLoaded", () => {
  for (const element of document.querySelectorAll(
    "[data-bs-toggle='tooltip']",
  )) {
    new Tooltip(element);
  }
});

window.bootstrap = {
  ...(window.bootstrap || {}),
  Tooltip,
};
