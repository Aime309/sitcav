import "../node_modules/simplebar/dist/simplebar.min";
import "iconify-icon/dist/iconify-icon.min";
import $ from "jquery";
import Toast from "bootstrap/js/dist/toast";
import Dropdown from "bootstrap/js/dist/dropdown";
import Tooltip from "bootstrap/js/dist/tooltip";
import ApexCharts from "apexcharts";

window.$ = $;
window.ApexCharts = ApexCharts;

for (const element of document.querySelectorAll(".toast")) {
  const toast = new Toast(element);

  toast.show();
}

for (const element of document.querySelectorAll(".dropdown-toggle")) {
  new Dropdown(element);
}

for (const element of document.querySelectorAll("[data-bs-toggle='tooltip']")) {
  new Tooltip(element);
}
