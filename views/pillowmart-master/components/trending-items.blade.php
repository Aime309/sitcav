<section class="trending_items">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="section_tittle text-center">
          <h2>Artículos en Tendencia</h2>
        </div>
      </div>
    </div>
    <div class="row">
      @foreach ($trendingItems as $item)
      <div class="col-lg-4 col-sm-6">
        <x-single-product-item :src="$item['src']" :name="$item['name']"
          :price="$item['price']" />
      </div>
      @endforeach
    </div>
  </div>
</section>
