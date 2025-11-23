<?php

$tiposNotificaciones = [
  [
    'for' => 'error',
    'in' => 'errores',
    'background' => 'danger',
    'icon' => 'bi bi-x-circle',
  ],
  [
    'for' => 'exito',
    'in' => 'exitos',
    'background' => 'success',
    'icon' => 'bi bi-check-circle',
  ],
  [
    'for' => 'advertencia',
    'in' => 'advertencias',
    'background' => 'warning',
    'icon' => 'bi bi-exclamation-triangle',
  ],
  [
    'for' => 'informacion',
    'in' => 'informaciones',
    'background' => 'info',
    'icon' => 'bi bi-info-circle',
  ],
];

?>

<div class="toast-container position-fixed top-0 end-0 p-3" style="width: max-content !important">
  <?php foreach ($tiposNotificaciones as $tipoNotificacion): ?>
    <template
      x-for="<?= $tipoNotificacion['for'] ?> in <?= $tipoNotificacion['in'] ?>"
      :key="<?= $tipoNotificacion['for'] ?>">
      <div class="toast toast-header text-bg-<?= $tipoNotificacion['background'] ?>">
        <i class="<?= $tipoNotificacion['icon'] ?> me-2"></i>
        <span class="me-auto" x-html="<?= $tipoNotificacion['for'] ?>"></span>
        <button class="btn-close" data-bs-dismiss="toast"></button>
      </div>
    </template>
  <?php endforeach ?>
</div>
