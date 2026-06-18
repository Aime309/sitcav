<x-layout :withslick="true">
  <x-breadcrumb>About</x-breadcrumb>

  <!-- product list part start-->
  <section class="about_us padding_top">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="about_us_content">
            <h5>Our Mission</h5>
            <h3>
              Donec imperdiet congue orci consequat mattis. Donec rutrum
              porttitor sollicitudin. Pellentesque id dolor tempor sapien
              feugiat ultrices nec sed neque.
            </h3>
            <div class="about_us_video">
              <img
                alt="#"
                class="img-fluid"
                src="img/about_us_video.png"
              >
              <a
                class="about_video_icon popup-youtube"
                href="https://www.youtube.com/watch?v=DWHB6nTyKDI"
              >
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- product list part end-->

  <x-feature />
  <x-client-review />
  <x-subscribe />
</x-layout>
