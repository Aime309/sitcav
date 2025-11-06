import Tab from "bootstrap/js/dist/tab";

document.addEventListener("DOMContentLoaded", () => {
  for (const element of document.querySelectorAll('[data-bs-toggle="tab"]')) {
    element.addEventListener("click", (event: Event) => {
      event.preventDefault();

      new Tab(element).show();
    });
  }
});
