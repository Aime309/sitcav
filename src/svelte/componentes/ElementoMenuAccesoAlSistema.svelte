<script>
  import { Link } from "svelte-routing";
  import tema from "../globales/tema";
  import Icono from "./Icono.svelte";
</script>

<div class="dropdown">
  <button
    class="nav-link text-bg-{$tema} bg-transparent"
    data-bs-toggle="dropdown"
  >
    <Icono nombre="user" tamañoFijo tamaño="2x" />
  </button>
  <div class="dropdown-menu dropdown-menu-end p-0">
    <li class="dropdown-item p-0">
      <Link
        class="btn btn-outline-primary w-100 rounded-0 d-flex align-items-center justify-content-between gap-2"
        to="./ingresar"
      >
        <Icono nombre="right-to-bracket" tamañoFijo />
        Ingresar
      </Link>
    </li>
    <li class="dropdown-item p-0">
      <Link
        class="btn btn-outline-primary w-100 rounded-0 d-flex align-items-center justify-content-between gap-2"
        to="./registrarse"
      >
        <Icono nombre="user-plus" tamañoFijo />
        Registrarse
      </Link>
    </li>
  </div>
</div>
