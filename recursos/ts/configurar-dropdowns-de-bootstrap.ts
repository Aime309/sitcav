import Dropdown from "bootstrap/js/dist/dropdown";

document.addEventListener("DOMContentLoaded", () => {
  for (const element of document.querySelectorAll(".dropdown-toggle")) {
    new Dropdown(element);
  }
});

// @ts-ignore
window.bootstrap = {
  ...(window.bootstrap || {}),
  Dropdown,
};
