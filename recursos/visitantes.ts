import "./scss/styles.scss";
import "@fontsource/inter/latin.css";
import "simplebar/dist/simplebar.min.css";
import "tabler-icons/iconfont/tabler-icons.min.css";

import "iconify-icon/dist/iconify-icon.min";
import Toast from "bootstrap/js/dist/toast";

for (const element of document.querySelectorAll(".toast")) {
  const toast = new Toast(element);

  toast.show();
}
