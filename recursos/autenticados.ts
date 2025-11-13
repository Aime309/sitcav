import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap-icons/font/bootstrap-icons.min.css";

import "./ts/tema";
import "./ts/mensajes";
import "./ts/tasa-de-pagina";
import "./ts/configurar-jquery";
import "./ts/configurar-tooltips-de-bootstrap";
import "./ts/configurar-offcanvas-de-bootstrap";
import "./ts/configurar-dropdowns-de-bootstrap";
import "./ts/configurar-collapse-de-bootstrap";
import "./ts/configurar-modals-de-bootstrap";
import "./ts/configurar-zxcvbn";
import Alpine from "alpinejs";

// @ts-ignore
window.Alpine = Alpine;

Alpine.start();
