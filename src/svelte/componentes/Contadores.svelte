<script>
  import Contador from "./Contador.svelte";
  import Icono from "./Icono.svelte";
</script>

<div class="row row-cols-2 row-cols-md-4">
  <Contador icono="a" descripcion="Amount Pending For Approval">
    500+ <Icono nombre="dollar-sign" />
  </Contador>
  <div class="col">
    <div class="alert alert-success back-widget-set text-center">
      <i class="fa fa-bars fa-5x"></i>
      <h3>300+ Tasks</h3>
      Pending For New Events
    </div>
  </div>
  <div class="col">
    <div class="alert alert-warning back-widget-set text-center">
      <i class="fa fa-recycle fa-5x"></i>
      <h3>56+ Calls</h3>
      To Be Made For New Orders
    </div>
  </div>
  <div class="col">
    <div class="alert alert-danger back-widget-set text-center">
      <i class="fa fa-briefcase fa-5x"></i>
      <h3>30+ Issues</h3>
      That Should Be Resolved Now
    </div>
  </div>
</div>
