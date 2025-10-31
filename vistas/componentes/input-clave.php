<?php

$id = uniqid();
$required ??= false;
$mostrarAdvertencias ??= true;

?>

<div x-data="{
  clave: `<?= $value ?? '' ?>`,

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
    const traducciones = {
      'Use a few words, avoid common phrases': 'Usa algunas palabras, evita frases comunes',
      'No need for symbols, digits, or uppercase letters': 'No es necesario usar símbolos, dígitos o letras mayúsculas',
      'Add another word or two. Uncommon words are better.': 'Añade una o dos palabras más. Las palabras poco comunes son mejores.',
      'Capitalization doesn\'t help very much': 'La capitalización no ayuda mucho',
      'Reversed words aren\'t much harder to guess': 'Las palabras al revés no son mucho más difíciles de adivinar',
    };

    return traducciones[sugerencia] || sugerencia;
  },

  obtenerAdvertenciaTraducida(advertencia) {
    if (advertencia.startsWith('Repeats like ')) {
      return 'Repeticiones como \'aaa\' son fáciles de adivinar';
    }

    const traducciones = {
      'This is a very common password': 'Esta es una contraseña muy común',
      'This is similar to a commonly used password': 'Esta es similar a una contraseña comúnmente usada',
      'A word by itself is easy to guess': 'Una sola palabra es fácil de adivinar',
      'Names and surnames by themselves are easy to guess': 'Nombres y apellidos por sí solos son fáciles de adivinar',
      'Common names and surnames are easy to guess': 'Nombres y apellidos comunes son fáciles de adivinar',
    };

    return traducciones[advertencia] || advertencia;
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
