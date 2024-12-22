<script>
  import { Route } from "svelte-routing";
  import VeltRouter from "./VeltRouter.svelte";
  import Login from "./pages/Login.svelte";

  import "bootstrap/dist/css/bootstrap.min.css";
  import "./styles/style.css";
  import "./styles/responsive.css";
  import "./styles/jquery.mCustomScrollbar.min.css";
  import "fancybox/dist/css/jquery.fancybox.css";
</script>

<VeltRouter>
  <Route path="/inicio">
    <Login />
  </Route>
</VeltRouter>
