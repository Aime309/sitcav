<script>
  import { Link, Route } from "svelte-routing";
  import VeltRouter from "./VeltRouter.svelte";
  import Ingreso from "./paginas/Ingreso.svelte";
  import Panel from "./paginas/Panel.svelte";
  import Registrarse from "./paginas/Registrarse.svelte";
</script>

<VeltRouter>
  <Route path="/">
    <Link to="./ingresar">/ingresar</Link>
  </Route>
  <Route path="/ingresar" component={Ingreso} />
  <Route path="/panel" component={Panel} />
  <Route path="/registrarse" component={Registrarse} />
</VeltRouter>

<style>
  :global(body) {
    overflow-y: scroll;
    min-height: 100vh;
  }
</style>
