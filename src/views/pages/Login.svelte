<script>
  import "bootstrap/dist/css/bootstrap.min.css";
  import "@fortawesome/fontawesome-free/css/all.min.css";
  import "../styles/style.css";
  import "../styles/responsive.css";
  import "../scripts/custom";

  function openNav() {
    document.getElementById("mySidepanel").style.width = "250px";
  }

  function closeNav() {
    document.getElementById("mySidepanel").style.width = "0";
  }
</script>

<!-- end loader -->
<div id="mySidepanel" class="sidepanel">
  <a href="javascript:void(0)" class="closebtn" onclick={closeNav}>×</a>
  <a href="index.html">Home </a>
  <a href="#about">About</a>
  <a href="#service">Services </a>
  <a href="#vehicles">Our Vehicles</a>
  <a href="#testimonial">Testimonial</a>
  <a href="#contact">Contact</a>
</div>
<!-- header -->
<header>
  <!-- header inner -->
  <div class="header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-4 col-sm-4">
          <div class="logo">
            <a href="index.html"><img src="images/logo.png" alt="#" /></a>
          </div>
        </div>
        <div class="col-md-8 col-sm-8">
          <div class="right_bottun">
            <ul class="conat_info d_none">
              <li>
                <a href="#"><i class="fa fa-user" aria-hidden="true"></i></a>
              </li>
              <li>
                <a href="#"><i class="fa fa-search" aria-hidden="true"></i></a>
              </li>
            </ul>
            <button class="openbtn" onclick={openNav}
              ><img src="images/menu_icon.png" alt="#" />
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>
<!-- end header inner -->
<!-- end header -->
<!-- banner -->
<section class="banner_main">
  <div id="banner1" class="carousel slide banner_slide" data-ride="carousel">
    <ol class="carousel-indicators">
      <li data-target="#banner1" data-slide-to="0" class="active"></li>
      <li data-target="#banner1" data-slide-to="1"></li>
      <li data-target="#banner1" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <div class="container-fluid">
          <div class="carousel-caption">
            <div class="row">
              <div class="col-md-7 col-lg-5">
                <div class="text-bg">
                  <h1>Best Logistic Company</h1>
                  <span
                    >Contrary to popular belief, Lorem Ipsum is not simply
                    random text. It has i</span
                  >
                  <a class="read_more" href="#">Contact Us</a>
                </div>
              </div>
              <div class="col-md-12 col-lg-7">
                <div class="row">
                  <div class="col-md-6">
                    <div class="ban_track">
                      <figure><img src="images/track.png" alt="#" /></figure>
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
      <div class="carousel-item">
        <div class="container-fluid">
          <div class="carousel-caption">
            <div class="row">
              <div class="col-md-7 col-lg-5">
                <div class="text-bg">
                  <h1>Best Logistic Company</h1>
                  <span
                    >Contrary to popular belief, Lorem Ipsum is not simply
                    random text. It has i</span
                  >
                  <a class="read_more" href="#">Contact Us</a>
                </div>
              </div>
              <div class="col-md-12 col-lg-7">
                <div class="row">
                  <div class="col-md-6">
                    <div class="ban_track">
                      <figure><img src="images/track.png" alt="#" /></figure>
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
      <div class="carousel-item">
        <div class="container-fluid">
          <div class="carousel-caption">
            <div class="row">
              <div class="col-md-7 col-lg-5">
                <div class="text-bg">
                  <h1>Best Logistic Company</h1>
                  <span
                    >Contrary to popular belief, Lorem Ipsum is not simply
                    random text. It has i</span
                  >
                  <a class="read_more" href="#">Contact Us</a>
                </div>
              </div>
              <div class="col-md-12 col-lg-7">
                <div class="row">
                  <div class="col-md-6">
                    <div class="ban_track">
                      <figure><img src="images/track.png" alt="#" /></figure>
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
    </div>
    <a
      class="carousel-control-prev"
      href="#banner1"
      role="button"
      data-slide="prev"
    >
      <i class="fa fa-angle-left" aria-hidden="true"></i>
    </a>
    <a
      class="carousel-control-next"
      href="#banner1"
      role="button"
      data-slide="next"
    >
      <i class="fa fa-angle-right" aria-hidden="true"></i>
    </a>
  </div>
</section>
<!-- end banner -->
<!-- about section -->
<div id="about" class="about">
  <div class="container">
    <div class="row d_flex">
      <div class="col-md-6">
        <div class="about_right">
          <figure><img src="images/about.png" alt="#" /></figure>
        </div>
      </div>
      <div class="col-md-6">
        <div class="titlepage">
          <h2>About Us</h2>
          <p>
            There are many variations of passages of Lorem Ipsum available, but
            the majority have suffered alteration in some form, by injected
            humour, There
          </p>
          <a class="read_more" href="#">Read More</a>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- about section -->
<!-- service section -->
<div id="service" class="service">
  <div class="container">
    <div class="row">
      <div class="col-md-10 offset-md-1">
        <div class="titlepage">
          <h2>Our Services</h2>
          <p>
            There are many variations of passages of Lorem Ipsum available, but
            the majority have suffered alteration in some form, by injected
            humour, There
          </p>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="service_main">
          <div class="service_box blu_colo">
            <i><img src="images/ser1.png" alt="#" /></i>
            <h4>FLY ANYWHERE</h4>
          </div>
          <div class="service_box yelldark_colo">
            <i><img src="images/ser2.png" alt="#" /></i>
            <h4>Cargo service</h4>
          </div>
          <div class="service_box yell_colo">
            <i><img src="images/ser3.png" alt="#" /></i>
            <h4>COURIER SERVICES</h4>
          </div>
          <div class="service_box yelldark_colo">
            <i><img src="images/ser4.png" alt="#" /></i>
            <h4>BOX STORAGE</h4>
          </div>
          <div class="service_box yell_colo">
            <i><img src="images/ser5.png" alt="#" /></i>
            <h4>100% safe</h4>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <a class="read_more" href="#">Read More</a>
      </div>
    </div>
  </div>
</div>
<!-- end service section -->
<!-- vehicles section -->
<section id="vehicles" class="vehicles">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="titlepage">
          <h2>Our Vehicles</h2>
          <p>
            nternet. It uses a dictionary of over 200 Latin words, combined with
            .
          </p>
        </div>
      </div>
    </div>
  </div>
  <div id="veh" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
      <li data-target="#veh" data-slide-to="0" class="active"></li>
      <li data-target="#veh" data-slide-to="1"></li>
      <li data-target="#veh" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <div class="container">
          <div class="carousel-caption">
            <div class="row">
              <div class="col-md-4">
                <div class="vehicles_truck">
                  <figure><img src="images/truc1.png" alt="#" /></figure>
                </div>
              </div>
              <div class="col-md-4">
                <div class="vehicles_truck">
                  <figure><img src="images/truc2.png" alt="#" /></figure>
                </div>
                <h3 class="blac_co">Truck</h3>
              </div>
              <div class="col-md-4">
                <div class="vehicles_truck">
                  <figure><img src="images/truc3.png" alt="#" /></figure>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="carousel-item">
        <div class="container">
          <div class="carousel-caption">
            <div class="row">
              <div class="col-md-4">
                <div class="vehicles_truck">
                  <figure><img src="images/truc1.png" alt="#" /></figure>
                </div>
              </div>
              <div class="col-md-4">
                <div class="vehicles_truck">
                  <figure><img src="images/truc2.png" alt="#" /></figure>
                </div>
                <h3 class="blac_co">Truck</h3>
              </div>
              <div class="col-md-4">
                <div class="vehicles_truck">
                  <figure><img src="images/truc3.png" alt="#" /></figure>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="carousel-item">
        <div class="container">
          <div class="carousel-caption">
            <div class="row">
              <div class="col-md-4">
                <div class="vehicles_truck">
                  <figure><img src="images/truc1.png" alt="#" /></figure>
                </div>
              </div>
              <div class="col-md-4">
                <div class="vehicles_truck">
                  <figure><img src="images/truc2.png" alt="#" /></figure>
                </div>
                <h3 class="blac_co">Truck</h3>
              </div>
              <div class="col-md-4">
                <div class="vehicles_truck">
                  <figure><img src="images/truc3.png" alt="#" /></figure>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <a
      class="carousel-control-prev"
      href="#veh"
      role="button"
      data-slide="prev"
    >
      <i class="fa fa-angle-left" aria-hidden="true"></i>
    </a>
    <a
      class="carousel-control-next"
      href="#veh"
      role="button"
      data-slide="next"
    >
      <i class="fa fa-angle-right" aria-hidden="true"></i>
    </a>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <a class="read_more" href="#">Read More</a>
      </div>
    </div>
  </div>
</section>
<!-- end vehicles section -->
<!-- testimonial section -->
<div id="testimonial" class="testimonial bottom_cross bottom_cross2">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="titlepage">
          <h2>Testimonials</h2>
          <p>
            nternet. It uses a dictionary of over 200 Latin words, combined with
            .
          </p>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div
          id="myCarousel"
          class="carousel slide testimonial_Carousel"
          data-ride="carousel"
        >
          <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
          </ol>
          <div class="carousel-inner">
            <div class="carousel-item active">
              <div class="container">
                <div class="carousel-caption">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="testimonial_box">
                        <figure><img src="images/our.png" alt="#" /></figure>
                        <h3>
                          Luda Johnson <br /><span class="kisu">( Ceo)</span>
                        </h3>
                        <p>
                          Contrary to popular belief, Lorem Ipsum is not simply
                          random text. It has roots in a piece of classical
                          Latin literature from 45 BC, making it over 2000 year
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="carousel-item">
              <div class="container">
                <div class="carousel-caption">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="testimonial_box">
                        <figure><img src="images/our.png" alt="#" /></figure>
                        <h3>
                          Luda Johnson <br /><span class="kisu">( Ceo)</span>
                        </h3>
                        <p>
                          Contrary to popular belief, Lorem Ipsum is not simply
                          random text. It has roots in a piece of classical
                          Latin literature from 45 BC, making it over 2000 year
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="carousel-item">
              <div class="container">
                <div class="carousel-caption">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="full cross_layout">
                        <div class="testimonial_box">
                          <figure><img src="images/our.png" alt="#" /></figure>
                          <h3>
                            Luda Johnson <br /><span class="kisu">( Ceo)</span>
                          </h3>
                          <p>
                            Contrary to popular belief, Lorem Ipsum is not
                            simply random text. It has roots in a piece of
                            classical Latin literature from 45 BC, making it
                            over 2000 year
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <a
            class="carousel-control-prev"
            href="#myCarousel"
            role="button"
            data-slide="prev"
          >
            <i class="fa fa-caret-left" aria-hidden="true"></i>
          </a>
          <a
            class="carousel-control-next"
            href="#myCarousel"
            role="button"
            data-slide="next"
          >
            <i class="fa fa-caret-right" aria-hidden="true"></i>
          </a>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <a class="read_more" href="#">Read More</a>
      </div>
    </div>
  </div>
</div>
<!-- end testimonial section -->
<!-- choose section -->
<div class="choose">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="titlepage">
          <h2>Why Choose Us</h2>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-5">
        <div class="choose_box">
          <i><img src="images/why1.png" alt="#" /></i>
          <h3>Our Vission</h3>
          <p>
            It is a long established fact that a reader will be distracted by
            the readable content of a page when looking at its layout. The point
            of using Lorem Ipsum is that it has a more-or-less normal
            distribution of letters,
          </p>
          <a class="read_more" href="#">Read More</a>
        </div>
      </div>
      <div class="col-md-5 offset-md-2">
        <div class="choose_box">
          <i><img src="images/why2.png" alt="#" /></i>
          <h3>Estimate Shipping</h3>
          <p>
            It is a long established fact that a reader will be distracted by
            the readable content of a page when looking at its layout. The point
            of using Lorem Ipsum is that it has a more-or-less normal
            distribution of letters,
          </p>
          <a class="read_more" href="#">Read More</a>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end choose section -->
<!-- contact section -->
<div id="contact" class="contact">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="titlepage">
          <h2>Requst A call for You</h2>
        </div>
      </div>
    </div>
  </div>
  <div class="con_bg">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-5">
          <form id="request" class="main_form">
            <div class="row">
              <div class="col-md-12">
                <input
                  class="contactus"
                  placeholder="Name"
                  type="type"
                  name="Name"
                />
              </div>
              <div class="col-md-12">
                <input
                  class="contactus"
                  placeholder="Email"
                  type="type"
                  name="Email"
                />
              </div>
              <div class="col-md-12">
                <input
                  class="contactus"
                  placeholder="Phone Number"
                  type="type"
                  name="Phone Number"
                />
              </div>
              <div class="col-md-12">
                <input
                  class="contactusmess"
                  placeholder="Message"
                  type="type"
                  Message="Name"
                />
              </div>
              <div class="col-md-12">
                <button class="send_btn">Send</button>
              </div>
            </div>
          </form>
        </div>
        <div class="col-md-7">
          <div class="co_tru">
            <figure><img src="images/truc4.png" alt="#" /></figure>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end contact section -->
<!--  footer -->
<footer>
  <div class="footer bottom_cross1">
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <ul class="location_icon">
            <li>
              <a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i></a
              >
              Address : Lorem Ipsum <br /> is simply dummy
            </li>
            <li>
              <a href="#"><i class="fa fa-phone" aria-hidden="true"></i></a
              >Phone : +(1234) 567 890
            </li>
            <li>
              <a href="#"><i class="fa fa-envelope" aria-hidden="true"></i></a
              >Email : demo@gmail.com
            </li>
          </ul>
          <form class="bottom_form">
            <h3>Newsletter</h3>
            <input
              class="enter"
              placeholder="Enter your email"
              type="text"
              name="Enter your email"
            />
            <button class="sub_btn">subscribe</button>
          </form>
        </div>
        <div class="col-md-8">
          <div class="map">
            <figure><img src="images/map.png" alt="#" /></figure>
          </div>
        </div>
      </div>
    </div>
    <div class="copyright">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <p>
              © 2019 All Rights Reserved. Design by<a
                href="https://html.design/"
              >
                Free Html Templates</a
              >
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>
<!-- end footer -->
