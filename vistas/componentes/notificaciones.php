<div class="toast-container position-fixed top-0 end-0 p-3" style="width: max-content !important">
  <template x-for="error in errores" :key="error">
    <div class="toast p-0 bg-transparent" x-init="new bootstrap.Toast($el).show()">
      <div class="toast-header text-danger">
        <i class="bi bi-x-circle-fill me-2"></i>
        <strong class="me-auto" x-text="error"></strong>
        <button class="btn-close" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </template>
  <template x-for="exito in exitos" :key="exito">
    <div class="toast" x-init="new bootstrap.Toast($el).show()">
      <div class="toast-header text-success">
        <i class="bi bi-check-circle-fill me-2"></i>
        <strong class="me-auto" x-text="exito"></strong>
        <button class="btn-close" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </template>
</div>
