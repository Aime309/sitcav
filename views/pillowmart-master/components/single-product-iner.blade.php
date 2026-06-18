<div class="single_product_iner">
  <div class="row align-items-center justify-content-between">
    <div class="col-lg-6 col-sm-6">
      <div class="single_product_img">
        <img class="img-fluid" src="{{ $src }}" />
        <img class="product_overlay img-fluid"
          src="./img/product_overlay.png" />
      </div>
    </div>
    <div class="col-lg-5 col-sm-6">
      <div class="single_product_content">
        <h5>Empezó desde ${{ $price }}</h5>
        <h2>
          <a href="javascript:">{{ $name }}</a>
        </h2>
        <a class="btn_3" href="{{ Flight::getUrl('ecommerce.product_list') }}">Explora Ahora</a>
      </div>
    </div>
  </div>
</div>
