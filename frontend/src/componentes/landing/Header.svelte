<script>
  const banners = [
    {
      image: "./images/banner-large-1.jpg",
      title: "Buy Electronic Gadgets at Affordable Prices",
      caption: "Tech gadgets are all about making your life easier",
    },
    {
      image: "./images/banner-large-2.jpg",
      title: "Cool Tech Gadgets Collection 2023",
      caption: "Tech gadgets are all about making your life easier",
    },
    {
      image: "./images/banner-large-3.jpg",
      title: "Sturdy And Multifunction New Gadgets",
      caption: "Tech gadgets are all about making your life easier",
    },
  ];
</script>

<div class="container-fluid">
  <div class="slideshow slide-in arrow-absolute text-white">
    <div class="swiper-wrapper">
      {#each banners as banner}
        <div class="swiper-slide jarallax">
          <img src={banner.image} class="jarallax-img" alt="slideshow" />
          <div class="banner-content w-100">
            <div class="container">
              <div class="row">
                <div class="col-md-10 px-5">
                  <h2 class="ls-0 mt-5 pt-5 txt-fx slide-up">
                    {banner.title}
                  </h2>
                  <p class="caption">{banner.caption}</p>
                  <a
                    href="#"
                    class="btn btn-outline-light text-uppercase rounded-3 px-4 py-3 mt-3"
                  >
                    Shop Now
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      {/each}
      <div class="swiper-pagination"></div>
    </div>
    <div class="pagination-wrapper position-absolute">
      <div class="container">
        <div class="slideshow-swiper-pagination"></div>
      </div>
    </div>
    <div class="icon-arrow icon-arrow-left text-white">
      <svg width="50" height="50" viewBox="0 0 24 24">
        <use xlink:href="#arrow-left"></use>
      </svg>
    </div>
    <div class="icon-arrow icon-arrow-right text-white">
      <svg width="50" height="50" viewBox="0 0 24 24">
        <use xlink:href="#arrow-right"></use>
      </svg>
    </div>
  </div>
</div>
