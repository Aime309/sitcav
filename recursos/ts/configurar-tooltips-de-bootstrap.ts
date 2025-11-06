import Tooltip from "bootstrap/js/dist/tooltip";

for (const element of document.querySelectorAll("[data-bs-toggle='tooltip']")) {
  new Tooltip(element);
}
