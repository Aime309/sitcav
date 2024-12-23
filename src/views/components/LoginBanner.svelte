<section class="banner_main">
  <div id="banner1" class="carousel slide banner_slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      {#each [0, 1, 2] as item}
        <div class="carousel-item" class:active={!item}>
          <div class="container-fluid">
            <div class="carousel-caption">
              <div class="row">
                <div class="col-md-7 col-lg-5">
                  <div class="text-bg">
                    <h1>Best Logistic Company</h1>
                    <span>
                      Contrary to popular belief, Lorem Ipsum is not simply
                      random text. It has i
                    </span>
                    <a class="read_more" href="./contactanos">Contact Us</a>
                  </div>
                </div>
                <div class="col-md-12 col-lg-7">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="ban_track">
                        <figure>
                          <img src="./images/track.png" alt="#" />
                        </figure>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <form class="transfot">
                        <div class="col-md-12">
                          <span>Professional Services</span>
                          <h3>Get your transport quote</h3>
                        </div>
                        <div class="col-md-12">
                          <input
                            class="transfot_form"
                            placeholder="Location"
                            type="text"
                            name="Location"
                          />
                        </div>
                        <div class="col-md-12">
                          <input
                            class="transfot_form"
                            placeholder="To destination"
                            type="text"
                            name="To destination"
                          />
                        </div>
                        <div class="col-md-12">
                          <input
                            class="transfot_form"
                            placeholder="Email"
                            type="text"
                            name="Email"
                          />
                        </div>
                        <div class="col-md-12">
                          <input
                            class="transfot_form"
                            placeholder="Contact Number"
                            type="text"
                            name="Contact Number"
                          />
                        </div>
                        <div class="col-md-12">
                          <button class="get_now">Get Now quote</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      {/each}
    </div>
    <button
      class="carousel-control-prev bg-primary"
      data-bs-target="#banner1"
      data-bs-slide="prev"
    >
      <span class="carousel-control-prev-icon"></span>
      <span class="visually-hidden">Ir a la página anterior del carrusel</span>
    </button>
    <button
      class="carousel-control-next bg-primary"
      data-bs-target="#banner1"
      data-bs-slide="next"
    >
      <span class="carousel-control-next-icon"></span>
      <span class="visually-hidden">Ir a la página siguiente del carrusel</span>
    </button>
  </div>
</section>
