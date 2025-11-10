<?php

$idModalFiltros = uniqid();

?>

<article
  class="card"
  x-data='{
    productos: JSON.parse(`<?= json_encode($productos) ?>`),
    categorias: JSON.parse(`<?= json_encode($categorias) ?>`),
    categoriaSeleccionada: "",
    ordenarPor: "",
    precios: "",
    color: "",
    busqueda: "",
    iconos: {
      categorias: {
        "Routers": "bi bi-router",
        "Modems": "bi bi-modem",
        "Laptops": "bi bi-laptop",
      },
      tipoOrden: {
        "Los más nuevos": "bi bi-newspaper",
        "Los más caros": "bi bi-sort-down",
        "Los más baratos": "bi bi-sort-up",
        "Descontinuados": "bi bi-archive",
      },
    },

    get productosFiltrados() {
      return this.productos.filter(producto => {
        let debeMostrarse = true;

        debeMostrarse &&= String(producto.codigo).toLowerCase().startsWith(this.busqueda.toLowerCase())
          || String(producto.nombre).toLowerCase().startsWith(this.busqueda.toLowerCase())
          || String(producto.descripcion).toLowerCase().startsWith(this.busqueda.toLowerCase());

        if (this.categoriaSeleccionada) {
          debeMostrarse &&= producto.id_categoria === this.categoriaSeleccionada;
        }

        if (this.precios) {
          if (this.precios === ">200") {
            debeMostrarse &&= producto.precio_unitario_actual_dolares > 200;
          } else {
            [min, max] = this.precios.split("-").map(Number);

            debeMostrarse &&= (
              producto.precio_unitario_actual_dolares >= min
              && producto.precio_unitario_actual_dolares <= max
            );
          }
        }

        return debeMostrarse;
      });
    },
  }'>
  <div class="d-flex">
    <div class="border-end d-none d-xl-block">
      <?php Flight::render('componentes/filtrosProductos') ?>
    </div>
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-5">
        <button
          class="btn d-xl-none"
          data-bs-toggle="offcanvas"
          href="#<?= $idModalFiltros ?>">
          <i class="navbar-toggler-icon"></i>
        </button>
        <h2 class="fs-5 mb-0 d-none d-xl-block">Productos</h2>
        <form class="position-relative">
          <input type="search" x-model="busqueda" class="form-control ps-5" placeholder="Buscar Producto">
          <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3"></i>
        </form>
      </div>
      <div class="row row-gap-4">
        <template x-for="producto in productosFiltrados">
          <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card h-100 overflow-hidden rounded-2 border">
              <div class="position-relative">
                <a :href="`./productos/${producto.id}`" class="hover-img d-block overflow-hidden ratio ratio-16x9">
                  <img
                    :src="producto.url_imagen || './recursos/imagenes/products/s11.jpg'"
                    class="card-img-top object-fit-contain" />
                </a>
                <button
                  @click="productosEnCarrito[producto.id] = (productosEnCarrito[producto.id] || 0) + 1"
                  class="text-bg-primary rounded-pill position-absolute bottom-0 end-0 me-2 mb-2"
                  data-bs-toggle="tooltip"
                  data-bs-placement="top"
                  data-bs-title="Añadir al carrito">
                  <i class="bi bi-basket-fill"></i>
                </button>
                <button
                  x-show="productosEnCarrito[producto.id] > 0"
                  @click="productosEnCarrito[producto.id] = Math.max((productosEnCarrito[producto.id] || 0) - 1, 0)"
                  class="text-bg-danger rounded-pill position-absolute bottom-0 end-0 me-5 mb-2"
                  data-bs-toggle="tooltip"
                  data-bs-placement="top"
                  data-bs-title="Eliminar del carrito">
                  <i class="bi bi-basket-fill"></i>
                  <span x-text="productosEnCarrito[producto.id]"></span>
                </button>
              </div>
              <div class="card-body">
                <span class="fs-6" x-text="producto.nombre"></span>
                <div class="d-flex align-items-center justify-content-between">
                  <strong class="fs-4">
                    <ins
                      class="text-decoration-none"
                      x-text="`$${producto.precio_unitario_actual_dolares}`"
                      data-bs-toggle="tooltip"
                      title="Efectivo"></ins>
                    <del
                      class="text-muted"
                      x-text="`$${producto.precio_unitario_actual_bcv}`"
                      data-bs-toggle="tooltip"
                      title="Transferencia">
                    </del>
                  </strong>
                  <ul
                    class="list-unstyled d-flex align-items-center mb-0"
                    x-data="{ nivel: 0 }">
                    <template x-for="star in [1, 2, 3, 4, 5]">
                      <li>
                        <button
                          class="me-1 btn btn-link p-0"
                          @click="nivel = star">
                          <i
                            class="bi text-warning"
                            :class="{
                              'bi-star': star > nivel,
                              'bi-star-fill': star <= nivel,
                            }">
                          </i>
                        </button>
                      </li>
                    </template>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </template>
      </div>
    </div>
  </div>

  <div class="offcanvas offcanvas-start" id="<?= $idModalFiltros ?>">
    <div class="offcanvas-body">
      <?php Flight::render('componentes/filtrosProductos') ?>
    </div>
  </div>
</article>
