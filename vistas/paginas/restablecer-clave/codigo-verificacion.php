<main class="card m-0">
  <header class="card-header text-center">
    <?php Flight::render('componentes/enlace-logo') ?>
    <p class="card-text">
      Te hemos enviado un código de verificación a tu correo. Ingresa el código de 6 dígitos en el campo de abajo.
    </p>
    <p class="card-text fw-bold">**********@****.***</p>
  </header>
  <form
    x-data="{ reenviar: false, foco: 1 }"
    @paste.prevent="
      const paste = $event.clipboardData.getData('text');
      const inputs = $el.querySelectorAll(`input[name='codigo[]']`);

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
    class="card-body d-grid gap-3">
    <div>
      <label class="form-label d-flex align-items-center justify-content-between">
        Escribe tu código de seguridad de 6 dígitos
        <button
          x-data="{
            get puedePegar() {
              return navigator.permissions
                .query({ name: 'clipboard-read' })
                .then((resultado) => resultado.state === 'granted' || resultado.state === 'prompt')
                .catch(() => false);
            }
          }"
          x-init="
            puedePegar.then((puedePegar) => {
              if (!puedePegar) {
                $el.disabled = true;
              }
            });
          "
          type="button"
          class="btn btn-primary"
          @click="
            const paste = await navigator.clipboard.readText();
            const inputs = $el.form.querySelectorAll(`input[name='codigo[]']`);

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
            tabindex="<?= $i + 1 ?>"
            type="number"
            min="0"
            max="9"
            name="codigo[]"
            :required="!reenviar"
            @keyup="
              $el.value = $el.value.slice(0, 1);

              if ($el.value.length >= 1 && foco < 6) {
                ++foco;
                $el.nextElementSibling.focus();
              }

              if (
                (
                  $event.inputType === 'deleteContentBackward'
                  || $event.key === 'Backspace'
                ) && $el.value.length === 0
                && foco > 1
              ) {
                --foco;
                $el.previousElementSibling.focus();
              }
            "
            class="form-control" />
        <?php endfor ?>
      </div>
    </div>
    <button @click="reenviar = false" class="btn btn-primary">
      Verificar mi cuenta
    </button>
    <div class="d-flex align-items-center justify-content-center gap-2">
      <p class="d-inline-block m-0">¿No recibiste el código?</p>
      <button
        @click="reenviar = true"
        type="submit"
        formaction="./restablecer-clave/solicitar-codigo"
        class="border-0 p-0 bg-transparent link-primary link-offset-3 text-decoration-underline">
        Reenviar código
      </button>
    </div>
  </form>
  <footer class="card-footer text-center">
    <p class="d-inline-block m-0">¿Recordaste tu contraseña?</p>
    <a class="link-primary link-offset-3" href="./ingresar">Ingresar</a>
  </footer>
</main>
