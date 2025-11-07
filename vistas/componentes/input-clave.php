<?php

use SITCAV\Enums\Traducciones;

$id = uniqid();
$required ??= false;
$mostrarAdvertencias ??= true;

?>

<div x-data="{
  clave: `<?= $value ?? '' ?>`,
  traducciones: {
    advertencias: <?= Traducciones::ADVERTENCIAS->comoObjetoJavaScript() ?>,
    sugerencias: <?= Traducciones::SUGERENCIAS->comoObjetoJavaScript() ?>,
  },

  get validacionClave() {
    return zxcvbn(this.clave);
  },

  get anchoEnPorcentaje() {
    if (!this.clave) {
      return '0%';
    }

    if (this.validacionClave.score <= 1) {
      return '33%';
    }

    if (this.validacionClave.score === 2) {
      return '66%';
    }

    return '100%';
  },

  obtenerSugerenciaTraducida(sugerencia) {
    return this.traducciones.sugerencias[sugerencia] || sugerencia;
  },

  obtenerAdvertenciaTraducida(advertencia) {
    return this.traducciones.advertencias[advertencia] || advertencia;
  }
}">
  <label for="<?= $id ?>" class="form-label"><?= $label ?? '' ?></label>
  <input
    type="password"
    name="<?= $name ?? '' ?>"
    <?= $required ? 'required' : '' ?>
    class="form-control"
    :class="{
      'is-invalid': clave && validacionClave.score <= 1,
      'is-valid': validacionClave.score >= 2,
    }"
    id="<?= $id ?>"
    x-model="clave" />
  <?php if ($mostrarAdvertencias): ?>
    <div
      class="invalid-feedback"
      x-text="obtenerAdvertenciaTraducida(validacionClave.feedback.warning)">
    </div>
  <?php endif ?>
  <div class="progress mt-2" style="height: 1rem">
    <div
      class="progress-bar progress-bar-striped progress-bar-animated"
      :class="{
        'text-bg-danger': validacionClave.score <= 1,
        'text-bg-warning': validacionClave.score === 2,
        'text-bg-success': validacionClave.score === 3,
      }"
      :style="`width: ${anchoEnPorcentaje}`">
      <template x-if="clave">
        <span x-text="{
          0: 'Muy débil',
          1: 'Débil',
          2: 'Aceptable',
          3: 'Fuerte',
        }[validacionClave.score]">
        </span>
      </template>
    </div>
  </div>
  <div class="form-text">
    <template x-for="sugerencia in validacionClave.feedback.suggestions">
      <div x-text="`&bullet; ${obtenerSugerenciaTraducida(sugerencia)}`"></div>
    </template>
  </div>
</div>
