<script lang="ts">
  import Swiper from "swiper/bundle";
  import { onMount } from "svelte";
  import { Autoplay } from "swiper/modules";
  import "swiper/css/bundle";
  import "swiper/css/autoplay";

  let swiper: HTMLElement;

  onMount(() => {
    new Swiper(swiper, {
      loop: true,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      modules: [Autoplay],
      autoplay: true,
    });
  });

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

<div bind:this={swiper} class="swiper">
  <div class="swiper-wrapper">
    {#each banners as banner}
      <div
        class="swiper-slide jarallax d-flex flex-column align-items-center justify-content-center"
        style:min-height="420px"
      >
        <img
          src={banner.image}
          class="position-absolute object-fit-cover z-n1"
          width="100%"
          alt={banner.title}
        />
        <h2 class="px-5">{banner.title}</h2>
        <p class="px-5">{banner.caption}</p>
        <!-- <a href="#" class="btn btn-outline-light text-uppercase">Shop Now</a> -->
      </div>
    {/each}
  </div>
  <div class="swiper-pagination my-5"></div>
  <div class="swiper-button-prev d-none d-xl-block"></div>
  <div class="swiper-button-next d-none d-xl-block"></div>
</div>
