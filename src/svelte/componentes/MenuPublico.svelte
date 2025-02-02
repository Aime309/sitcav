<script>
  import { Link } from "svelte-routing";
  import AlternadorDelTema from "./AlternadorDelTema.svelte";
  import { Navbar, Nav } from "@sveltestrap/sveltestrap";
  import { tooltip } from "../acciones/tooltip";
  import ElementoMenuAccesoAlSistema from "./ElementoMenuAccesoAlSistema.svelte";
</script>

<Navbar expand sticky="top">
  <Link class="navbar-brand" to="./">
    <img
      src="./imagenes/favicon.png"
      alt="Logo de SITCAV"
      use:tooltip
      title="SITCAV"
      height={55}
    />
  </Link>
  <Nav navbar class="ms-auto">
    <ElementoMenuAccesoAlSistema />
    <AlternadorDelTema />
  </Nav>
</Navbar>
