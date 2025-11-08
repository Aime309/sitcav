import "@fontsource/inter/latin.css";
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap-icons/font/bootstrap-icons.min.css";

import "./ts/tema";
import "./ts/mensajes";
import "./ts/tasa-de-pagina";
import "./ts/configurar-jquery";
import "./ts/configurar-tooltips-de-bootstrap";
import "./ts/configurar-offcanvas-de-bootstrap";
import Alpine from "alpinejs";

// @ts-ignore
window.Alpine = Alpine;

Alpine.start();
