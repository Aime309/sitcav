import Toast from "bootstrap/js/dist/toast";

document.addEventListener("DOMContentLoaded", () => {
  for (const element of document.querySelectorAll(".toast")) {
    const toast = new Toast(element);

    toast.show();
  }
});

window.bootstrap = {
  ...(window.bootstrap || {}),
  Toast,
};
