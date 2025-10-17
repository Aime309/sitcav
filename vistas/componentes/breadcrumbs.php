<div class="card card-body">
  <div class="row align-items-center">
    <div class="col-12">
      <div class="d-sm-flex align-items-center justify-space-between">
        <h4 class="fw-semibold fs-4 mb-4 mb-md-0 card-title"><?= $tituloPagina ?></h4>
        <nav aria-label="breadcrumb" class="ms-auto">
          <ol class="breadcrumb">
            <li class="breadcrumb-item d-flex align-items-center">
              <a class="text-muted text-decoration-none d-flex" href="./">
                <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
              </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
              <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">
                <?= $tituloPagina ?>
              </span>
            </li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
</div>
