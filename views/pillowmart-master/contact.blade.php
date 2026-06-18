<x-layout :withslick="true">
  <x-breadcrumb>contact</x-breadcrumb>
  <!-- ================ contact section start ================= -->
  <section class="contact-section section_padding">
    <div class="container">
      <div class="d-none d-sm-block mb-5 pb-4">
        <div
          id="map"
          style="height: 480px;"
        ></div>
        <script>
          function initMap() {
            var uluru = {
              lat: -25.363,
              lng: 131.044
            };
            var grayStyles = [{
                featureType: "all",
                stylers: [{
                    saturation: -90
                  },
                  {
                    lightness: 50
                  }
                ]
              },
              {
                elementType: 'labels.text.fill',
                stylers: [{
                  color: '#ccdee9'
                }]
              }
            ];
            var map = new google.maps.Map(document.getElementById('map'), {
              center: {
                lat: -31.197,
                lng: 150.744
              },
              zoom: 9,
              styles: grayStyles,
              scrollwheel: false
            });
          }
        </script>
        <script
          src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDpfS1oRGreGSBU5HHjMmQ3o5NLw7VdJ6I&callback=initMap"
        ></script>

      </div>

      <div class="row">
        <div class="col-12">
          <h2 class="contact-title">Get in Touch</h2>
        </div>
        <div class="col-lg-8">
          <form
            action="contact_process.php"
            class="form-contact contact_form"
            id="contactForm"
            method="post"
            novalidate="novalidate"
          >
            <div class="row">
              <div class="col-12">
                <div class="form-group">

                  <textarea
                    class="form-control w-100"
                    cols="30"
                    id="message"
                    name="message"
                    onblur="this.placeholder = 'Enter Message'"
                    onfocus="this.placeholder = ''"
                    placeholder='Enter Message'
                    rows="9"
                  ></textarea>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <input
                    class="form-control"
                    id="name"
                    name="name"
                    onblur="this.placeholder = 'Enter your name'"
                    onfocus="this.placeholder = ''"
                    placeholder='Enter your name'
                    type="text"
                  >
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <input
                    class="form-control"
                    id="email"
                    name="email"
                    onblur="this.placeholder = 'Enter email address'"
                    onfocus="this.placeholder = ''"
                    placeholder='Enter email address'
                    type="email"
                  >
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <input
                    class="form-control"
                    id="subject"
                    name="subject"
                    onblur="this.placeholder = 'Enter Subject'"
                    onfocus="this.placeholder = ''"
                    placeholder='Enter Subject'
                    type="text"
                  >
                </div>
              </div>
            </div>
            <div class="form-group mt-3">
              <a
                class="btn_3 button-contactForm"
                href="#"
              >Send
                Message</a>
            </div>
          </form>
        </div>
        <div class="col-lg-4">
          <div class="media contact-info">
            <span class="contact-info__icon"><i class="ti-home"></i></span>
            <div class="media-body">
              <h3>Buttonwood, California.</h3>
              <p>Rosemead, CA 91770</p>
            </div>
          </div>
          <div class="media contact-info">
            <span class="contact-info__icon"><i class="ti-tablet"></i></span>
            <div class="media-body">
              <h3>00 (440) 9865 562</h3>
              <p>Mon to Fri 9am to 6pm</p>
            </div>
          </div>
          <div class="media contact-info">
            <span class="contact-info__icon"><i class="ti-email"></i></span>
            <div class="media-body">
              <h3>support@colorlib.com</h3>
              <p>Send us your query anytime!</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ================ contact section end ================= -->
</x-layout>
