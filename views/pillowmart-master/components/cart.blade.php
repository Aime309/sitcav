<section class="cart_area section_padding">
  <div class="container">
    <div class="cart_inner">
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Producto</th>
              <th>Precio</th>
              <th>Cantidad</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            <template x-for="product in reservedProducts">
              <tr>
                <td>
                  <div class="media">
                    <div class="d-flex">
                      <img x-bind:src="product.sources[0]" />
                    </div>
                    <div class="media-body">
                      <p x-text="product.name"></p>
                    </div>
                  </div>
                </td>
                <td>
                  <h5 x-text="`$${product.price.toFixed(2)}`"></h5>
                </td>
                <td>
                  <div class="product_count">
                    <span class="input-number-decrement"
                      x-on:click="product.quantity--">
                      <i class="ti-minus"></i>
                    </span>
                    <input class="input-number" max="10" min="0" type="number"
                      value="1" x-model="product.quantity" />
                    <span class="input-number-increment"
                      x-on:click="product.quantity++">
                      <i class="ti-plus"></i>
                    </span>
                  </div>
                </td>
                <td>
                  <h5
                    x-text="`$${(product.price * product.quantity).toFixed(2)}`">
                  </h5>
                </td>
              </tr>
            </template>
            <tr>
              <td></td>
              <td></td>
              <td>
                <h5>Subtotal</h5>
              </td>
              <td>
                <h5 x-text="`$${reservedProductsSubTotal.toFixed(2)}`"></h5>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="checkout_btn_inner float-right">
          <a class="btn_1" href="{{ Flight::getUrl('ecommerce.product_list') }}" x-on:click.prevent="
            reservedProducts.forEach(reservedProduct => {
              if (reservedProduct.quantity) {
                return;
              }

              removeReservedProduct(product.{{ auth()->config('id.key') }});
            });

            location.href = $el.href;
          ">
            Continuar Comprando
          </a>
          <a class="btn_1 checkout_btn_1" href="{{ Flight::getUrl('ecommerce.checkout') }}">
            Proceder a pagar
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
