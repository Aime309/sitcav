<?php

$errores = (array) flash()->display('errores');
$exitos = (array) flash()->display('exitos');

?>

<div class="toast-container position-fixed top-0 end-0 p-3">
  <?php foreach ($errores as $error): ?>
    <div class="toast">
      <div class="toast-header text-danger">
        <i class="bi bi-x-circle-fill me-2"></i>
        <strong class="me-auto"><?= $error ?></strong>
        <button class="btn-close" data-bs-dismiss="toast"></button>
      </div>
    </div>
  <?php endforeach ?>
  <?php foreach ($exitos as $exito): ?>
    <div class="toast">
      <div class="toast-header text-success">
        <i class="bi bi-check-circle-fill me-2"></i>
        <strong class="me-auto"><?= $exito ?></strong>
        <button class="btn-close" data-bs-dismiss="toast"></button>
      </div>
    </div>
  <?php endforeach ?>
</div>
