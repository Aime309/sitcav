<?php

$required ??= false;

?>

<div>
  <label class="form-label"><?= $label ?? '' ?></label>
  <input
    class="form-control"
    name="<?= $name ?? '' ?>"
    <?= $required ? 'required' : '' ?>
    type="<?= $type ?? 'text' ?>" />
</div>
