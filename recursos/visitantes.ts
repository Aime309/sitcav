import "@fontsource/inter/latin.css";
import "bootstrap/dist/css/bootstrap.min.css";

import "./ts/configurar-toasts-de-bootstrap";
import "./ts/configurar-tabs-de-bootstrap";
import "./ts/configurar-tooltips-de-bootstrap";
import "./ts/configurar-zxcvbn";
import "./ts/tema";
import Alpine from "alpinejs";

// @ts-ignore
window.Alpine = Alpine;

Alpine.start();
