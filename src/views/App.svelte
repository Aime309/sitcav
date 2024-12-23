<script>
  import { Link, Route } from "svelte-routing";
  import VeltRouter from "./VeltRouter.svelte";
  import Login from "./pages/Login.svelte";
</script>

<VeltRouter>
  <Route path="/">
    <Link to="./inicio">/inicio</Link>
  </Route>
  <Route path="/inicio">
    <Login />
  </Route>
</VeltRouter>
