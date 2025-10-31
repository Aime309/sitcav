<div class="min-vh-100 d-flex align-items-center justify-content-center p-3">
  <div class="col-md-8 col-lg-6 col-xxl-3">
    <div class="card m-0">
      <header class="card-header text-center">
        <?php Flight::render('componentes/enlace-logo') ?>
        <p class="card-text">
          Te hemos enviado un código de verificación a tu correo. Ingresa el código de 6 dígitos en el campo de abajo.
        </p>
        <p class="card-text">
          <strong>******@****.****</strong>
        </p>
      </header>
      <form
        x-data="{ reenviar: false, foco: 1 }"
        @paste.prevent="
          const paste = $event.clipboardData.getData('text');
          const inputs = $el.querySelectorAll('input[name=\'codigo[]\']');

          for (let i = 0; i < inputs.length; ++i) {
            inputs[i].value = paste[i] || '';
          }

          foco = Math.min(paste.length + 1, 6);

          if (foco <= 6) {
            inputs[foco - 1].focus();
          }
        "
        action="./restablecer-clave/verificar-codigo"
        method="post"
        class="card-body">
        <div class="mb-4">
          <label for="input-respuesta-secreta" class="form-label d-flex align-items-center justify-content-between">
            Escribe tu código de seguridad de 6 dígitos
            <button
              type="button"
              class="btn btn-primary"
              @click="
                const paste = await navigator.clipboard.readText();
                const inputs = $el.form.querySelectorAll('input[name=\'codigo[]\']');

                for (let i = 0; i < inputs.length; ++i) {
                  inputs[i].value = paste[i] || '';
                }

                foco = Math.min(paste.length + 1, 6);

                if (foco <= 6) {
                  inputs[foco - 1].focus();
                }
              ">
              Pegar
            </button>
          </label>
          <div class="d-flex align-items-center gap-2 gap-sm-3">
            <?php for ($i = 0; $i < 6; ++$i): ?>
              <input
                type="number"
                min="0"
                max="9"
                name="codigo[]"
                :required="!reenviar"
                @input="
                  $el.value = $el.value.slice(0, 1);

                  if ($el.value.length >= 1 && foco < 6) {
                    ++foco;
                    $el.nextElementSibling.focus();
                  }

                  if ($event.inputType === 'deleteContentBackward' && foco > 1) {
                    --foco;
                    $el.previousElementSibling.focus();
                  }
                "
                class="form-control" />
            <?php endfor ?>
          </div>
        </div>
        <button @click="reenviar = false" type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">
          Verificar mi cuenta
        </button>
        <div class="d-flex align-items-center justify-content-center gap-2">
          <span class="fs-4 fw-bold">¿No recibiste el código?</span>
          <button
            @click="reenviar = true"
            type="submit"
            formaction="./restablecer-clave/solicitar-codigo"
            class="border-0 p-0 bg-transparent link-primary fw-bold ms-2">
            Reenviar código
          </button>
        </div>
      </form>
      <footer class="card-footer text-center">
        <span class="fs-4 fw-bold">¿Recordaste tu contraseña?</span>
        <a class="link-primary fw-bold ms-2" href="./ingresar">Ingresar</a>
      </footer>
    </div>
  </div>
</div>
