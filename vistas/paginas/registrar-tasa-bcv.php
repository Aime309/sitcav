<?php

$dolaresDeLaApi = @file_get_contents('https://ve.dolarapi.com/v1/dolares') ?: '[{"promedio":"Error de conexión"}]';
$tasaDePagina = json_decode($dolaresDeLaApi)[0]->promedio;

?>

<div class="modal" id="modal-actualizar-tasa-bcv" data-bs-backdrop="static">
  <div class="modal-dialog">
    <form class="modal-content" action="./cotizaciones" method="post">
      <header class="modal-header">
        <h1 class="modal-title">Cotización de hoy</h1>
      </header>
      <div class="modal-body">
        <div class="form-floating mb-3">
          <input
            type="number"
            step=".01"
            name="nueva_tasa"
            required
            placeholder="Tasa BCV"
            value="<?= $ultimaCotizacion->tasa_bcv ?>"
            class="form-control" />
          <label>Tasa BCV</label>
        </div>
        <p class="lead m-0">
          Tasa según
          <a href="https://www.bcv.org.ve/">bcv.org.ve</a>
          <output class="badge" :class="(tasaDePagina === 'Cargando' || tasaDePagina === 'Error de conexión') ? 'text-bg-danger' : 'text-bg-info'" x-text="tasaDePagina"></output>
        </p>
      </div>
      <footer class="modal-footer">
        <button class="btn btn-primary">Actualizar</button>
      </footer>
    </form>
  </div>
</div>

<script defer src="./recursos/compilados/registrar-tasa-bcv.js"></script>
