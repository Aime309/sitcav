<script>
  import TarjetaMenu from "./TarjetaMenu.svelte";
</script>

<h2 class="text-center">Bienvenido a tu sistema integral de análisis de ventas</h2>
<h3 class="text-center">¡Comienza a registrar tu información!</h3>

<hr />

<div
  class="row row-cols-2 row-cols-sm-3 row-cols-md-4 g-4"
>
  <TarjetaMenu url="./clientes" icono="people-group" fondo="success">
    Clientes
  </TarjetaMenu>

  <TarjetaMenu url="./productos" icono="boxes-stacked" fondo="warning">
    Productos
  </TarjetaMenu>

  <TarjetaMenu url="./compras" icono="bag-shopping" fondo="danger">
    Compras
  </TarjetaMenu>

  <TarjetaMenu url="./ventas/nueva" icono="dollar-sign" fondo="danger">
    Venta rápida
  </TarjetaMenu>

  <TarjetaMenu url="./estadisticas" icono="chart-pie" fondo="success">
    Estadísticas
  </TarjetaMenu>

  <TarjetaMenu url="./negocio" icono="store" fondo="info">
    Mi negocio
  </TarjetaMenu>

  <TarjetaMenu url="./reportes" icono="list-ul" fondo="primary">
    Reportes
  </TarjetaMenu>
</div>
