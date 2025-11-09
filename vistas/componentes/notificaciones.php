<div class="toast-container position-fixed top-0 end-0 p-3" style="width: max-content !important">
  <template x-for="error in errores" :key="error">
    <div class="toast" x-init="new bootstrap.Toast($el).show()">
      <div class="toast-header text-bg-danger">
        <i class="bi bi-x-circle me-2"></i>
        <span class="me-auto" x-html="error"></span>
        <button class="btn-close" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </template>
  <template x-for="exito in exitos" :key="exito">
    <div class="toast" x-init="new bootstrap.Toast($el).show()">
      <div class="toast-header text-bg-success">
        <i class="bi bi-check-circle me-2"></i>
        <span class="me-auto" x-html="exito"></span>
        <button class="btn-close" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </template>
  <template x-for="advertencia in advertencias" :key="advertencia">
    <div class="toast" x-init="new bootstrap.Toast($el).show()">
      <div class="toast-header text-bg-warning">
        <i class="bi bi-check-circle me-2"></i>
        <span class="me-auto" x-html="advertencia"></span>
        <button class="btn-close" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </template>
  <template x-for="informacion in informaciones" :key="informacion">
    <div class="toast" x-init="new bootstrap.Toast($el).show()">
      <div class="toast-header text-bg-info">
        <i class="bi bi-check-circle me-2"></i>
        <span class="me-auto" x-html="informacion"></span>
        <button class="btn-close" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </template>
</div>
