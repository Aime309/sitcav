import Tab from "bootstrap/js/dist/tab";

for (const element of document.querySelectorAll('[data-bs-toggle="tab"]')) {
  element.addEventListener("click", (event) => {
    event.preventDefault();

    new Tab(element).show();
  });
}
