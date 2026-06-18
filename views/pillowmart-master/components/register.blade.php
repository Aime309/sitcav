<section class="login_part section_padding py-0">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6 col-md-6">
        <div class="login_part_text text-center">
          <div class="login_part_text_iner">
            <h2>¿Ya tienes una cuenta?</h2>
            <p>
              Cada día se producen avances en la ciencia y la tecnología, y un
              buen ejemplo de ello es el acceso rápido a lo que necesitas.
            </p>
            <a class="btn_3" href="{{ Flight::getUrl('ecommerce.login') }}">Ingresar</a>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-6 order-first">
        <div class="login_part_form">
          <div class="login_part_form_iner">
            <h3>
              ¡ Únete a nuestra Tienda !<br />
              Por favor completa tus datos para crear una cuenta
            </h3>
            @if ($message)
            <x-alert type="danger">
              <ul>
                @foreach ($message as $message)
                <li>{{ $message }}</li>
                @endforeach
              </ul>
            </x-alert>
            @endif
            <div class="btn-group d-flex">
              <a class="btn btn-danger" href="{{ Flight::getUrl('ecommerce.oauth2.google') }}">
                <i class="fab fa-google mr-2"></i>Crearse una cuenta usando
                Google+
              </a>
            </div>
            <hr />
            <form class="row contact_form" method="post">
              <div class="col-md-12 form-group p_star">
                <input class="form-control" name="email"
                  placeholder="Correo electrónico" required type="email">
              </div>
              <div class="col-md-12 form-group p_star">
                <input class="form-control" name="password"
                  placeholder="Contraseña" required type="password">
              </div>
              <div class="col-md-12 form-group">
                <button class="btn_3" type="submit">
                  crearse una cuenta
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
