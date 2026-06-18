<section class="product_list section_padding" x-data='{
  products: @json($products),
  search: "",
  category: "",
  type: "",

  get filteredProducts() {
    return this.products.filter(product => {
      let match = true;

      if (this.search) {
        match &&= (
          product.name.toLowerCase().startsWith(this.search.toLowerCase())
          || product.description.toLowerCase().startsWith(this.search.toLowerCase())
        );
      }

      if (this.category) {
        match &&= product.category === this.category;
      }

      if (this.type) {
        match &&= product.type === this.type;
      }

      return match;
    });
  },
}'>
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <div class="product_sidebar">
          <div class="single_sedebar">
            <input placeholder="Palabra clave de búsqueda" type="search"
              x-model="search" />
            <i class="ti-search"></i>
          </div>
          <div class="single_sedebar">
            <div class="select_option">
              <div class="select_option_list">
                Categoría
                <i class="right fas fa-caret-down"></i>
              </div>
              <div class="select_option_dropdown" style="display: none">
                <p>
                  <a href="javascript:" x-on:click="category = ''">Todas</a>
                </p>
                @foreach ($categories as $category)
                <p>
                  <a href="javascript:"
                    x-on:click='category = @json($category)'>
                    {{ $category }}
                  </a>
                </p>
                @endforeach
              </div>
            </div>
          </div>
          <div class="single_sedebar">
            <div class="select_option">
              <div class="select_option_list">
                Tipo
                <i class="right fas fa-caret-down"></i>
              </div>
              <div class="select_option_dropdown" style="display: none">
                <p>
                  <a href="javascript:" x-on:click="type = ''">Todos</a>
                </p>
                @foreach ($types as $type)
                <p>
                  <a href="javascript:" x-on:click='type = @json($type)'>
                    {{ $type }}
                  </a>
                </p>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <div class="product_list">
          <div class="row">
            <template x-for="product in filteredProducts">
              <div class="col-lg-6 col-sm-6">
                <div class="single_product_item">
                  <img class="img-fluid" x-bind:src="product.sources[0]" />
                  <h3>
                    <a data-href="{{ Flight::getUrl('ecommerce.single-product.@' . auth()->config('id.key')) }}" x-bind:href="`${$el.dataset.href}/${product.{{ auth()->config('id.key') }}}`"
                      x-text="product.name">
                    </a>
                  </h3>
                  <p x-text="`From $${product.price}`"></p>
                </div>
              </div>
            </template>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
