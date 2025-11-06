import Modal from "bootstrap/js/dist/modal";

document.addEventListener("DOMContentLoaded", () => {
  for (const element of document.querySelectorAll(".modal")) {
    new Modal(element);
  }
});

window.bootstrap = {
  ...(window.bootstrap || {}),
  Modal,
};
