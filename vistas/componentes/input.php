<?php

use SITCAV\Enums\ClaveSesion;

$required ??= false;
$name ??= '';
$error = ((array) session()->get('leaf.flash'))[ClaveSesion::MENSAJES_ERRORES->name][$name] ?? '';

?>

<div class="position-relative">
  <label class="form-label"><?= $label ?? '' ?></label>
  <input
    class="form-control <?= $error ? 'is-invalid' : '' ?>"
    name="<?= $name ?>"
    <?= $required ? 'required' : '' ?>
    type="<?= $type ?? 'text' ?>"
    value="<?= $value ?? '' ?>"
    @keydown="$el.classList.remove('is-invalid')" />
  <div class="invalid-feedback"><?= $error ?></div>
</div>
