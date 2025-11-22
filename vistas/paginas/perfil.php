<?php

use SITCAV\Enums\Rol;

$formularios = [
  'cuenta' => [
    'id' => uniqid(),
    'icono' => 'bi bi-person-circle',
    'label' => 'Cuenta',
  ],
  // 'notificaciones' => [
  //   'id' => uniqid(),
  //   'icono' => 'bi bi-bell',
  //   'label' => 'Notificaciones',
  // ],
  // 'seguridad' => [
  //   'id' => uniqid(),
  //   'icono' => 'bi bi-lock',
  //   'label' => 'Seguridad',
  // ],
];

?>

<div class="card">
  <ul class="nav nav-pills user-profile-tab">
    <?php foreach ($formularios as $indice => $formulario): ?>
      <li class="nav-item">
        <button class="nav-link position-relative rounded-0 <?= $indice === 'cuenta' ? 'active' : '' ?> d-flex align-items-center justify-content-center bg-transparent py-3" data-bs-toggle="pill" data-bs-target="#<?= $formulario['id'] ?>" type="button">
          <i class="<?= $formulario['icono'] ?> me-2 fs-6"></i>
          <span class="d-none d-md-block"><?= $formulario['label'] ?></span>
        </button>
      </li>
    <?php endforeach ?>
  </ul>
  <div class="card-body">
    <div class="tab-content">
      <form
        method="post"
        class="tab-pane fade show active"
        id="<?= $formularios['cuenta']['id'] ?? uniqid() ?>"
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
                <h4 class="card-title">Cambiar perfil</h4>
                <p class="card-subtitle mb-4">Cambia tu foto de perfil aquí</p>
                <div class="text-center">
                  <img
                    :src="avatar || '<?= auth()->user()?->url_imagen ?: './recursos/imagenes/profile/user-1.jpg' ?>'"
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
                <h4 class="card-title">Cambiar contraseña</h4>
                <p class="card-subtitle mb-4">Para cambiar tu contraseña por favor confirma aquí</p>
                <div class="mb-3">
                  <label class="form-label">Contraseña anterior</label>
                  <input
                    type="password"
                    name="clave_anterior"
                    class="form-control" />
                </div>
                <div class="mb-3">
                  <?php Flight::render('componentes/input-clave', [
                    'label' => 'Nueva contraseña',
                    'name' => 'nueva_clave',
                    'model' => 'nueva_clave',
                  ]) ?>
                </div>
                <div class="mb-3">
                  <label class="form-label">Confirmar contraseña</label>
                  <input
                    type="password"
                    name="confirmar_clave"
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
                      <input type="email" name="correo" class="form-control" value="<?= auth()->user()?->email ?>" />
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label class="form-label">Cédula</label>
                      <input type="number" name="cedula" class="form-control" value="<?= auth()->user()?->cedula ?>" />
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="d-flex align-items-center justify-content-end mt-4 gap-3">
                      <button class="btn btn-primary">Actualizar</button>
                      <a href="<?= Flight::request()->referrer ?>" class="btn bg-danger-subtle text-danger">
                        Cancelar
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
      <div class="tab-pane fade" id="<?= $formularios['notificaciones']['id'] ?? uniqid() ?>">
        <div class="row justify-content-center">
          <div class="col-lg-9">
            <div class="card border shadow-none">
              <div class="card-body p-4">
                <h4 class="card-title">Notification Preferences</h4>
                <p class="card-subtitle mb-4">
                  Select the notificaitons ou would like to receive via email. Please note that you cannot opt
                  out of receving service
                  messages, such as payment, security or legal notifications.
                </p>
                <form class="mb-7">
                  <label for="exampleInputtext5" class="form-label">Email Address*</label>
                  <input type="text" class="form-control" id="exampleInputtext5" placeholder="" required="">
                  <p class="mb-0">Required for notificaitons.</p>
                </form>
                <div>
                  <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-center gap-3">
                      <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                        <i class="ti ti-article text-dark d-block fs-7" width="22" height="22"></i>
                      </div>
                      <div>
                        <h5 class="fs-4 fw-semibold">Our newsletter</h5>
                        <p class="mb-0">We'll always let you know about important changes</p>
                      </div>
                    </div>
                    <div class="form-check form-switch mb-0">
                      <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked">
                    </div>
                  </div>
                  <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-center gap-3">
                      <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                        <i class="ti ti-checkbox text-dark d-block fs-7" width="22" height="22"></i>
                      </div>
                      <div>
                        <h5 class="fs-4 fw-semibold">Order Confirmation</h5>
                        <p class="mb-0">You will be notified when customer order any product</p>
                      </div>
                    </div>
                    <div class="form-check form-switch mb-0">
                      <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked1" checked="">
                    </div>
                  </div>
                  <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-center gap-3">
                      <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                        <i class="ti ti-clock-hour-4 text-dark d-block fs-7" width="22" height="22"></i>
                      </div>
                      <div>
                        <h5 class="fs-4 fw-semibold">Order Status Changed</h5>
                        <p class="mb-0">You will be notified when customer make changes to the order</p>
                      </div>
                    </div>
                    <div class="form-check form-switch mb-0">
                      <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked2" checked="">
                    </div>
                  </div>
                  <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-center gap-3">
                      <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                        <i class="ti ti-truck-delivery text-dark d-block fs-7" width="22" height="22"></i>
                      </div>
                      <div>
                        <h5 class="fs-4 fw-semibold">Order Delivered</h5>
                        <p class="mb-0">You will be notified once the order is delivered</p>
                      </div>
                    </div>
                    <div class="form-check form-switch mb-0">
                      <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked3">
                    </div>
                  </div>
                  <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                      <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                        <i class="ti ti-mail text-dark d-block fs-7" width="22" height="22"></i>
                      </div>
                      <div>
                        <h5 class="fs-4 fw-semibold">Email Notification</h5>
                        <p class="mb-0">Turn on email notificaiton to get updates through email</p>
                      </div>
                    </div>
                    <div class="form-check form-switch mb-0">
                      <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked4" checked="">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-9">
            <div class="card border shadow-none">
              <div class="card-body p-4">
                <h4 class="card-title">Date &amp; Time</h4>
                <p class="card-subtitle">Time zones and calendar display settings.</p>
                <div class="d-flex align-items-center justify-content-between mt-7">
                  <div class="d-flex align-items-center gap-3">
                    <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                      <i class="ti ti-clock-hour-4 text-dark d-block fs-7" width="22" height="22"></i>
                    </div>
                    <div>
                      <p class="mb-0">Time zone</p>
                      <h5 class="fs-4 fw-semibold">(UTC + 02:00) Athens, Bucharet</h5>
                    </div>
                  </div>
                  <a class="text-dark fs-6 d-flex align-items-center justify-content-center bg-transparent p-2 fs-4 rounded-circle" href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Download">
                    <i class="ti ti-download"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-9">
            <div class="card border shadow-none">
              <div class="card-body p-4">
                <h4 class="card-title">Ignore Tracking</h4>
                <div class="d-flex align-items-center justify-content-between mt-7">
                  <div class="d-flex align-items-center gap-3">
                    <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                      <i class="ti ti-player-pause text-dark d-block fs-7" width="22" height="22"></i>
                    </div>
                    <div>
                      <h5 class="fs-4 fw-semibold">Ignore Browser Tracking</h5>
                      <p class="mb-0">Browser Cookie</p>
                    </div>
                  </div>
                  <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked5">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="d-flex align-items-center justify-content-end gap-6">
              <button class="btn btn-primary">Save</button>
              <button class="btn bg-danger-subtle text-danger">Cancel</button>
            </div>
          </div>
        </div>
      </div>
      <div class="tab-pane fade" id="<?= $formularios['seguridad']['id'] ?? uniqid() ?>">
        <div class="row">
          <div class="col-lg-8">
            <div class="card border shadow-none">
              <div class="card-body p-4">
                <h4 class="card-title mb-3">Two-factor Authentication</h4>
                <div class="d-flex align-items-center justify-content-between pb-7">
                  <p class="card-subtitle mb-0">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Corporis sapiente
                    sunt earum officiis laboriosam ut.</p>
                  <button class="btn btn-primary">Enable</button>
                </div>
                <div class="d-flex align-items-center justify-content-between py-3 border-top">
                  <div>
                    <h5 class="fs-4 fw-semibold mb-0">Authentication App</h5>
                    <p class="mb-0">Google auth app</p>
                  </div>
                  <button class="btn bg-primary-subtle text-primary">Setup</button>
                </div>
                <div class="d-flex align-items-center justify-content-between py-3 border-top">
                  <div>
                    <h5 class="fs-4 fw-semibold mb-0">Another e-mail</h5>
                    <p class="mb-0">E-mail to send verification link</p>
                  </div>
                  <button class="btn bg-primary-subtle text-primary">Setup</button>
                </div>
                <div class="d-flex align-items-center justify-content-between py-3 border-top">
                  <div>
                    <h5 class="fs-4 fw-semibold mb-0">SMS Recovery</h5>
                    <p class="mb-0">Your phone number or something</p>
                  </div>
                  <button class="btn bg-primary-subtle text-primary">Setup</button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="card">
              <div class="card-body p-4">
                <div class="text-bg-light rounded-1 p-6 d-inline-flex align-items-center justify-content-center mb-3">
                  <i class="ti ti-device-laptop text-primary d-block fs-7" width="22" height="22"></i>
                </div>
                <h4 class="card-title mb-0">Devices</h4>
                <p class="mb-3">Lorem ipsum dolor sit amet consectetur adipisicing elit Rem.</p>
                <button class="btn btn-primary mb-4">Sign out from all devices</button>
                <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
                  <div class="d-flex align-items-center gap-3">
                    <i class="ti ti-device-mobile text-dark d-block fs-7" width="26" height="26"></i>
                    <div>
                      <h5 class="fs-4 fw-semibold mb-0">iPhone 14</h5>
                      <p class="mb-0">London UK, Oct 23 at 1:15 AM</p>
                    </div>
                  </div>
                  <a class="text-dark fs-6 d-flex align-items-center justify-content-center bg-transparent p-2 fs-4 rounded-circle" href="javascript:void(0)">
                    <i class="ti ti-dots-vertical"></i>
                  </a>
                </div>
                <div class="d-flex align-items-center justify-content-between py-3">
                  <div class="d-flex align-items-center gap-3">
                    <i class="ti ti-device-laptop text-dark d-block fs-7" width="26" height="26"></i>
                    <div>
                      <h5 class="fs-4 fw-semibold mb-0">Macbook Air</h5>
                      <p class="mb-0">Gujarat India, Oct 24 at 3:15 AM</p>
                    </div>
                  </div>
                  <a class="text-dark fs-6 d-flex align-items-center justify-content-center bg-transparent p-2 fs-4 rounded-circle" href="javascript:void(0)">
                    <i class="ti ti-dots-vertical"></i>
                  </a>
                </div>
                <button class="btn bg-primary-subtle text-primary w-100 py-1">Need Help ?</button>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="d-flex align-items-center justify-content-end gap-6">
              <button class="btn btn-primary">Save</button>
              <button class="btn bg-danger-subtle text-danger">Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
