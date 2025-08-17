<script>
  const products = [
    {
      id: 1,
      name: "Phone Cover 5.6 inches",
      image: "./images/product-small-1.jpg",
      price: "$95.00",
      link: "single-product.html",
    },
    {
      id: 2,
      name: "Smart Phone 128GB RAM",
      image: "./images/product-small-2.jpg",
      price: "$55.00",
      link: "single-product.html",
    },
    {
      id: 3,
      name: "Smart Phone 128GB RAM",
      image: "./images/product-small-3.jpg",
      price: "$55.00",
      link: "single-product.html",
    },
    {
      id: 4,
      name: "Smart Phone 128GB RAM",
      image: "./images/product-small-4.jpg",
      price: "$55.00",
      link: "single-product.html",
    },
    {
      id: 5,
      name: "22 Inch LED Display",
      image: "./images/product-small-5.jpg",
      price: "$65.00",
      link: "single-product.html",
    },
  ];
</script>

<section id="new-arrival" class="new-arrival py-5">
  <div class="container-fluid">
    <div
      class="d-flex flex-wrap justify-content-between align-items-center mt-5 mb-3"
    >
      <h4 class="text-uppercase">Our New Arrivals</h4>
      <a href="shop.html" class="btn-link">View All Products</a>
    </div>

    <div data-aos="fade-in">
      <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5">
        {#each products as product (product.id)}
          <div class="col">
            <div class="product-item hover-effect-slide">
              <div class="image-holder position-relative">
                <a href={product.link}>
                  <img
                    src={product.image}
                    alt="categories"
                    class="product-image img-fluid"
                  />
                </a>
                <a href="#" class="btn btn-primary w-100 mt-2 rounded-1">
                  Add to cart
                </a>
              </div>
              <div
                class="product-content d-flex flex-column flex-md-row justify-content-between align-items-md-center"
              >
                <h5 class="fs-5 mt-3 me-3">
                  <a href={product.link}>{product.name}</a>
                </h5>
                <span class="ms-3">{product.price}</span>
              </div>
            </div>
          </div>
        {/each}
      </div>
    </div>
  </div>
</section>
