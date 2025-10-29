import "@fontsource/inter/latin.css";
import "simplebar/dist/simplebar.min.css";
import "tabler-icons/iconfont/tabler-icons.min.css";
import "pure-css-loader/dist/loader-bouncing.css";

import "iconify-icon/dist/iconify-icon.min";
import "alpinejs/dist/cdn.min";
import Toast from "bootstrap/js/dist/toast";
import Tab from "bootstrap/js/dist/tab";
import zxcvbn from "zxcvbn";

for (const element of document.querySelectorAll(".toast")) {
  const toast = new Toast(element);

  toast.show();
}

for (const element of document.querySelectorAll('[data-bs-toggle="tab"]')) {
  element.addEventListener("click", (event) => {
    event.preventDefault();

    new Tab(element).show();
  });
}

// @ts-ignore
window.zxcvbn = zxcvbn;

window.bootstrap = {
  ...(window.bootstrap || {}),
  Toast: Toast,
};
