<div class="product_image_area" x-data='{
  product: @json($product),

  get reservedProduct() {
    return reservedProducts.find(reservedProduct => reservedProduct.id === this.product.id);
  },

  quantity: 1,
  }' x-effect="
  if (quantity === 0) {
    return removeReservedProduct(reservedProduct);
  }

  if (quantity < 0) {
    quantity = 0;
  }

  if (quantity > (product.stock || 0)) {
    quantity = product.stock || 0;
  }
  " x-init="quantity = reservedProduct?.quantity || 1">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="product_img_slide owl-carousel">
          @foreach ($product["sources"] as $src)
          <div class="single_product_img">
            <img alt="#" class="img-fluid" src="{{ $src }}" />
          </div>
          @endforeach
        </div>
      </div>
      <div class="col-lg-8">
        <div class="single_product_text text-center">
          <h3>{{ $product["name"] }}</h3>
          <p>{{ $product["description"] }}</p>
          <div class="card_area">
            <div class="product_count_area" style="user-select: none">
              <p>Cantidad</p>
              <div class="product_count d-inline-block">
                <span class="product_count_item inumber-decrement"
                  x-on:click="quantity--">
                  <i class="ti-minus"></i>
                </span>
                <input class="product_count_item input-number" min="0"
                  type="number" x-bind:max="product.stock || 0"
                  x-model="quantity">
                <span class="product_count_item number-increment"
                  x-on:click="quantity++">
                  <i class="ti-plus"></i>
                </span>
              </div>
              <p x-text="`$${product.price * quantity}`"></p>
            </div>
            <div class="add_to_cart">
              <a class="btn btn_3 {{ auth()->user() ?: "disabled" }}"
                href="javascript:" x-on:click="
                  if (reservedProduct) {
                    return reservedProduct.quantity += quantity;
                  }

                  if (!quantity) {
                    return removeReservedProduct(product);
                  }

                  reservedProducts.push({ ...product, quantity });
                ">
                añadir al carrito
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
