import "@fontsource/inter/latin.css";
import "./scss/styles.scss";
import "jquery/dist/jquery.min";
import "iconify-icon/dist/iconify-icon.min";
import Toast from "bootstrap/js/dist/toast";

for (const element of document.querySelectorAll(".toast")) {
  const toast = new Toast(element);

  toast.show();
}
