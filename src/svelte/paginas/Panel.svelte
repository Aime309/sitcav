<script>
  import titulo from "../globales/titulo";
  import Logo from "../componentes/Logo.svelte";
  import Notificaciones from "../componentes/Notificaciones.svelte";
  import Boton from "../componentes/Boton.svelte";
  import { Navbar, NavbarToggler, Offcanvas } from "@sveltestrap/sveltestrap";
  import Icono from "../componentes/Icono.svelte";
  import TarjetaPerfil from "../componentes/TarjetaPerfil.svelte";
  import { Link } from "svelte-routing";

  $titulo = "Panel";
  let menuAbierto = false;

  function alternarMenu() {
    menuAbierto = !menuAbierto;
  }
</script>

<header
  class="d-flex align-items-center justify-content-between container py-2"
>
  <Navbar container={false} class="gap-5">
    <NavbarToggler
      class="border-0"
      target="#menu-lateral-privado"
      on:click={alternarMenu}
    >
      <Icono nombre="bars" tamaño="2x" />
    </NavbarToggler>
    <Link to="./">
      <Logo />
    </Link>
  </Navbar>
  <div class="d-flex align-items-center gap-5">
    <Notificaciones />
    <Boton>Cerrar sesión</Boton>
  </div>
</header>

<Offcanvas
  id="menu-lateral-privado"
  isOpen={menuAbierto}
  toggle={alternarMenu}
  scroll
>
  <TarjetaPerfil />
</Offcanvas>
