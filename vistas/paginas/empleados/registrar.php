<?php

use SITCAV\Enums\Rol;

?>

<form
  class="card card-body"
  method="post"
  action="./empleados"
  x-data="{
    avatar: '',
    fileReader: new FileReader(),
    nueva_clave: '',
  }"
  x-init="fileReader.onload = () => (avatar = fileReader.result)">
  <div class="row">
    <div class="col-lg-6 d-flex align-items-stretch">
      <div class="card w-100 border position-relative overflow-hidden">
        <div class="card-body p-4">
          <h4 class="card-title">Perfil del empleado</h4>
          <p class="card-subtitle mb-4">Elige la foto de perfil aquí</p>
          <div class="text-center">
            <img
              :src="avatar || './recursos/imagenes/profile/user-1.jpg'"
              class="img-fluid rounded-circle"
              width="120"
              height="120" />
            <div class="d-flex align-items-center justify-content-center my-4 gap-3">
              <label class="btn btn-primary">
                Subir
                <input
                  type="file"
                  accept="image/jpeg,image/png,image/gif"
                  class="d-none"
                  @change="fileReader.readAsDataURL($el.files[0])" />
                <input
                  type="url"
                  class="d-none"
                  name="url_imagen"
                  x-model="avatar" />
              </label>
              <button
                type="button"
                class="btn bg-danger-subtle text-danger"
                @click="avatar = ''">
                Reiniciar
              </button>
            </div>
            <p class="mb-0">Se permite JPG, GIF o PNG. Tamaño máximo de 800KB</p>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6 d-flex align-items-stretch">
      <div class="card w-100 border position-relative overflow-hidden">
        <div class="card-body p-4">
          <h4 class="card-title">Contraseña</h4>
          <p class="card-subtitle mb-4">
            Para establecer una contraseña por favor confirme aquí
          </p>
          <div class="mb-3">
            <?php Flight::render('componentes/input-clave', [
              'label' => 'Nueva contraseña',
              'name' => 'clave',
              'model' => 'nueva_clave',
              'required' => true,
            ]) ?>
          </div>
          <div class="mb-3">
            <label class="form-label">Confirmar contraseña</label>
            <input
              type="password"
              name="confirmar_clave"
              required
              class="form-control"
              :pattern="nueva_clave"
              title="Ambas contraseñas deben coincidir" />
          </div>
        </div>
      </div>
    </div>
    <div class="col-12">
      <div class="card w-100 border position-relative overflow-hidden mb-0">
        <div class="card-body p-4">
          <h4 class="card-title">Detalles personales</h4>
          <p class="card-subtitle mb-4">Establece detalles personales aquí</p>
          <div class="row">
            <div class="col-lg-6">
              <div class="mb-3">
                <label class="form-label">Correo electrónico</label>
                <input type="email" name="correo" class="form-control" />
              </div>
              <div class="mb-3">
                <label class="form-label">Rol</label>
                <select class="form-select" name="rol" required>
                  <option><?= Rol::EMPLEADO_SUPERIOR->value ?></option>
                  <option selected><?= Rol::VENDEDOR->value ?></option>
                </select>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="mb-3">
                <label class="form-label">Cédula</label>
                <input type="number" name="cedula" required class="form-control" />
              </div>
            </div>
            <div class="col-12">
              <div class="d-flex align-items-center justify-content-end mt-4 gap-3">
                <button class="btn btn-primary">Contratar</button>
                <a href="./empleados" class="btn bg-danger-subtle text-danger">Cancelar</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
