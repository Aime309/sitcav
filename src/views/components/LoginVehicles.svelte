<section id="vehicles" class="vehicles">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="titlepage">
          <h2>Our Vehicles</h2>
          <p>
            Internet. It uses a dictionary of over 200 Latin words, combined
            with .
          </p>
        </div>
      </div>
    </div>
  </div>
  <div id="veh" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      {#each [0, 1, 2] as truckPage}
        <div class="carousel-item" class:active={!truckPage}>
          <div class="container">
            <div class="carousel-caption">
              <div class="row">
                {#each [1, 2, 3] as truckId}
                  <div class="col-md-4">
                    <div class="vehicles_truck">
                      <figure>
                        <img src="images/truc{truckId}.png" alt="#" />
                      </figure>
                    </div>
                  </div>
                {/each}
              </div>
            </div>
          </div>
        </div>
      {/each}
    </div>
    <button
      class="carousel-control-prev bg-primary"
      data-bs-target="#veh"
      data-bs-slide="prev"
    >
      <span class="carousel-control-prev-icon"></span>
      <span class="visually-hidden">Ir a la página anterior del carrusel</span>
    </button>
    <button
      class="carousel-control-next bg-primary"
      data-bs-target="#veh"
      data-bs-slide="next"
    >
      <span class="carousel-control-next-icon"></span>
      <span class="visually-hidden">Ir a la página siguiente del carrusel</span>
    </button>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <a class="read_more" href="./read-more">Read More</a>
      </div>
    </div>
  </div>
</section>
