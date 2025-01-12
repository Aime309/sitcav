<script>
  import { Link, Route } from "svelte-routing";
  import VeltRouter from "./VeltRouter.svelte";
  import Login from "./pages/Login.svelte";
  import Dashboard from "./pages/Dashboard.svelte";
  import Register from "./pages/Register.svelte";
</script>

<VeltRouter>
  <Route path="/">
    <Link to="./ingresar">/ingresar</Link>
  </Route>
  <Route path="/ingresar" component={Login} />
  <Route path="/panel" component={Dashboard} />
  <Route path="/registrarse" component={Register} />

</VeltRouter>
