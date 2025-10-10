<?php

$publicaciones = [
  [
    "titulo" => "Garmins Instinct Crossover is a rugged hybrid smartwatch",
    "categoria" => "Social",
    "imagen" => "./recursos/imagenes/blog/blog-img1.jpg",
    "autor_imagen" => "./recursos/imagenes/profile/user-3.jpg",
    "autor_nombre" => "Georgeanna Ramero",
    "lectura_tiempo" => "2 min Read",
    "vistas" => 9125,
    "comentarios" => 3,
    "fecha" => "Mon, Dec 19"
  ],
  [
    "titulo" => "Intel loses bid to revive antitrust case against patent foe Fortress",
    "categoria" => "Gadget",
    "imagen" => "./recursos/imagenes/blog/blog-img2.jpg",
    "autor_imagen" => "./recursos/imagenes/profile/user-2.jpg",
    "autor_nombre" => "Georgeanna Ramero",
    "lectura_tiempo" => "2 min Read",
    "vistas" => 4150,
    "comentarios" => 38,
    "fecha" => "Sun, Dec 18"
  ],
  [
    "titulo" => "COVID outbreak deepens as more lockdowns loom in China",
    "categoria" => "Health",
    "imagen" => "./recursos/imagenes/blog/blog-img3.jpg",
    "autor_imagen" => "./recursos/imagenes/profile/user-3.jpg",
    "autor_nombre" => "Georgeanna Ramero",
    "lectura_tiempo" => "2 min Read",
    "vistas" => 9480,
    "comentarios" => 12,
    "fecha" => "Sat, Dec 17"
  ],
];

?>

<div class="row">
  <?php foreach ($publicaciones as $publicacion): ?>
    <div class="col-lg-4">
      <div class="card overflow-hidden hover-img h-100">
        <div class="position-relative">
          <a href="javascript:void(0)">
            <img src="<?= $publicacion['imagen'] ?>" class="card-img-top" alt="materialM-img" />
          </a>
          <span class="badge text-bg-light text-dark fs-2 lh-sm mb-9 me-9 py-1 px-2 fw-semibold position-absolute bottom-0 end-0">
            <?= $publicacion['lectura_tiempo'] ?>
          </span>
          <img
            src="<?= $publicacion['autor_imagen'] ?>"
            alt="materialM-img"
            class="img-fluid rounded-circle position-absolute bottom-0 start-0 mb-n9 ms-9"
            width="40"
            height="40"
            data-bs-toggle="tooltip"
            data-bs-placement="top"
            data-bs-title="<?= $publicacion['autor_nombre'] ?>" />
        </div>
        <div class="card-body p-4">
          <span class="badge text-bg-light fs-2 py-1 px-2 lh-sm  mt-3">
            <?= $publicacion['categoria'] ?>
          </span>
          <a class="d-block my-4 fs-5 text-dark fw-semibold link-primary" href="">
            <?= $publicacion['titulo'] ?>
          </a>
          <div class="d-flex align-items-center gap-4">
            <div class="d-flex align-items-center gap-2">
              <i class="ti ti-eye text-dark fs-5"></i>
              <?= $publicacion['vistas'] ?>
            </div>
            <div class="d-flex align-items-center gap-2">
              <i class="ti ti-message-2 text-dark fs-5"></i>
              <?= $publicacion['comentarios'] ?>
            </div>
            <div class="d-flex align-items-center fs-2 ms-auto">
              <i class="ti ti-point text-dark"></i>
              <?= $publicacion['fecha'] ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach ?>
</div>
