<script>
  import { Route, Router } from "svelte-routing";
  import Ingreso from "./paginas/Ingreso.svelte";
  import Panel from "./paginas/Panel.svelte";
  import Registrarse from "./paginas/Registrarse.svelte";
  import Presentacion from "./paginas/Presentacion.svelte";
</script>

<Router basepath={document.baseURI.replace(location.origin, "")}>
  <Route path="/" component={Presentacion} />
  <Route path="/ingresar" component={Ingreso} />
  <Route path="/panel" component={Panel} />
  <Route path="/registrarse" component={Registrarse} />
</Router>

<style>
  :global(body) {
    overflow-y: scroll;
    min-height: 100vh;
  }
</style>
