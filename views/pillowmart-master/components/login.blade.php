<section class="login_part section_padding py-0">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6 col-md-6">
        <div class="login_part_text text-center">
          <div class="login_part_text_iner">
            <h2>¿Nuevo en nuestra Tienda?</h2>
            <p>
              Cada día se producen avances en la ciencia y la tecnología,
              y un buen ejemplo de ello es el
            </p>
            <a class="btn_3" href="{{ Flight::getUrl('ecommerce.register') }}">Crearse una Cuenta</a>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-6">
        <div class="login_part_form">
          <div class="login_part_form_iner">
            <h3>
              ¡ Bienvenido de Vuelta !<br />
              Por favor Ingresa ahora
            </h3>
            @if (is_array($message))
            <x-alert type="danger">
              <ul>
                @foreach ($message as $error)
                <li>{{ $error }}</li>
                @endforeach
              </ul>
            </x-alert>
            @endif
            <div class="btn-group d-flex">
              <a class="btn btn-danger" href="{{ Flight::getUrl('ecommerce.oauth2.google') }}">
                <i class="fab fa-google mr-2"></i>
                Ingresa usando Google+
              </a>
            </div>
            <hr />
            <form class="row contact_form" method="post">
              <div class="col-md-12 form-group p_star">
                <input class="form-control" name="email"
                  placeholder="Correo electrónico" required type="email"
                  value="{{ $_ENV['APP_ENV'] === 'local' ? 'test@test.com' : '' }}" />
              </div>
              <div class="col-md-12 form-group p_star">
                <input class="form-control" name="password"
                  placeholder="Contraseña" required type="password"
                  value="{{ $_ENV['APP_ENV'] === 'local' ? 'test' : '' }}" />
              </div>
              <div class="col-md-12 form-group">
                <div class="creat_account d-flex align-items-center">
                  <input id="f-option" name="remember" type="checkbox">
                  <label for="f-option"
                    style="user-select: none">Recuérdame</label>
                </div>
                <button class="btn_3" type="submit">
                  ingresar
                </button>
                <a class="lost_pass" href="javascript:">
                  ¿olvidó la contraseña?
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
