<script>
  const categories = [
    {
      id: 1,
      title: "Electronic Gadgets",
      image: "./images/category-thumb-1.jpg",
      link: "shop-four-column-wide.html",
    },
    {
      id: 2,
      title: "Cool Stuff",
      image: "./images/category-thumb-2.jpg",
      link: "shop-four-column-wide.html",
    },
    {
      id: 3,
      title: "Accessories",
      image: "./images/category-thumb-3.jpg",
      link: "shop-four-column-wide.html",
    },
    {
      id: 4,
      title: "Display Units",
      image: "./images/category-thumb-4.jpg",
      link: "shop-four-column-wide.html",
    },
  ];
</script>

<section class="categories overflow-hidden">
  <div class="container-fluid">
    <div class="row g-5">
      {#each categories as category, index (category.id)}
        <div class="col-md-3">
          <span class="d-block py-3 text-white text-uppercase fs-7 ls-1">
            {index + 1}. {category.title}
          </span>
          <div class="jarallax position-relative mb-4" style:height="480px">
            <img
              src={category.image}
              alt="categories"
              class="jarallax-img img-fluid opacity-25"
            />
            <div
              class="category-content position-absolute bottom-0 p-5 text-uppercase"
            >
              <a href="shop-four-column-wide.html" class="text-white btn-link">
                Shop it Now
              </a>
            </div>
          </div>
        </div>
      {/each}
    </div>
  </div>
</section>
