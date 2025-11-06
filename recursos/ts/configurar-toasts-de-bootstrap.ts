import Toast from "bootstrap/js/dist/toast";

for (const element of document.querySelectorAll(".toast")) {
  const toast = new Toast(element);

  toast.show();
}

window.bootstrap = {
  ...(window.bootstrap || {}),
  Toast: Toast,
};
