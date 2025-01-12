<script>
  import { Link, Route } from "svelte-routing";
  import VeltRouter from "./VeltRouter.svelte";
  import Login from "./pages/Login.svelte";
  import Dashboard from "./pages/Dashboard.svelte";
  import Register from "./pages/Register.svelte";
  import "bootstrap/dist/css/bootstrap.min.css";
  import "@fortawesome/fontawesome-free/css/all.min.css";
  import "bootstrap/dist/js/bootstrap.bundle.min";
  import { onMount } from "svelte";
  import { Tooltip } from "bootstrap";

  onMount(() => {
    document
      .querySelectorAll('[data-bs-toggle="tooltip"]')
      .forEach((element) => {
        new Tooltip(element);
      });
  });
</script>

<VeltRouter>
  <Route path="/">
    <Link to="./ingresar">/ingresar</Link>
  </Route>
  <Route path="/ingresar" component={Login} />
  <Route path="/panel" component={Dashboard} />
  <Route path="/registrarse" component={Register} />
</VeltRouter>

<style>
  :global(body) {
    overflow-y: scroll;
  }
</style>
