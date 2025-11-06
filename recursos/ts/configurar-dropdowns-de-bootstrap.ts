import Dropdown from "bootstrap/js/dist/dropdown";

for (const element of document.querySelectorAll(".dropdown-toggle")) {
  new Dropdown(element);
}
