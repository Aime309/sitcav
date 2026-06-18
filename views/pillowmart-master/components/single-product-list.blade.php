<section class="single_product_list">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        @foreach ($products as $product)
        <x-single-product-iner :src="$product['src']" :price="$product['price']"
          :name="$product['name']" />
        @endforeach
      </div>
    </div>
  </div>
</section>
