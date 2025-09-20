<?php

use SITCAV\Modelos\Usuario;

// function renderizarSvelte(): void
// {
//   Flight::render('estructuras/base');
// }

Flight::route('GET /ingresar', function (): void {
  $hrefBase = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);

  echo <<<html
  <base href="$hrefBase" />
  <h1>Ingresar</h1>
  <form method="post">
    <input type="number" name="cedula" required placeholder="Cédula" />
    <input type="password" name="clave" required placeholder="Contraseña" />
    <a href="./restablecer-clave">¿Has olvidado tu contraseña?</a>
    <button>Ingresar</button>
  </form>
  html;
});

Flight::route('POST /ingresar', function (): void {
  $credenciales = Flight::request()->data;

  if (auth()->login([
    'cedula' => $credenciales->cedula,
    'clave_encriptada' => $credenciales->clave,
  ])) {
    exit('BIENVENIDO <a href="./salir">Cerrar sesión</a>');
  } else {
    dd(auth()->errors());
  }
});

Flight::route('/salir', function (): void {
  auth()->logout();
  Flight::redirect('/ingresar');
});

Flight::route('GET /registrarse', function (): void {});

Flight::route('POST /registrarse', function (): void {});

Flight::route('GET /restablecer-clave', function (): void {
  $hrefBase = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);

  echo <<<html
  <base href="$hrefBase" />
  <h1>Restablecer contraseña - Paso 1</h1>
  <form method="post">
    <input type="number" name="cedula" required placeholder="Cédula" />
    <button>Continuar</button>
  </form>
  html;
});

Flight::route('POST /restablecer-clave', function (): void {
  $cedula = Flight::request()->data->cedula;
  $hrefBase = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
  $usuario = Usuario::query()->where('cedula', $cedula)->first();

  if (!$usuario) {
    $intentos = session()->get('recuperar-clave.intentos') ?? 0;
    ++$intentos;

    if ($intentos >= 3) {
      session()->remove('recuperar-clave.intentos');

      exit("<script>alert('Has alcanzado el número máximo de intentos. Por favor, inténtalo más tarde.'); location.href = './ingresar'</script>");
    }

    session()->set('recuperar-clave.intentos', $intentos);
    exit("<script>alert(`Llevas $intentos intentos`); location.href = './restablecer-clave'</script>");
  }

  flash()->set($usuario->id, 'usuarios.id');

  echo <<<html
  <base href="$hrefBase" />
  <h1>Restablecer contraseña - Paso 2</h1>
  <form method="post" action="./restablecer-clave/2">
    <label>
      $usuario->pregunta_secreta
      <input type="password" name="respuesta" required placeholder="Respuesta" />
    </label>
    <button>Continuar</button>
  </form>
  html;
});

Flight::route('POST /restablecer-clave/2', function (): void {
  $respuesta = Flight::request()->data->respuesta;
  $usuario = Usuario::query()->find(flash()->display('usuarios.id'));
  $hrefBase = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);

  try {
    $usuario->asegurarValidezRespuestaSecreta($respuesta);
    flash()->set($usuario->id, 'usuarios.id');

    echo <<<html
    <base href="$hrefBase" />
    <h1>Restablecer contraseña - Paso 3</h1>
    <form action="./restablecer-clave/3" method="post">
      <input type="password" name="nueva_clave" placeholder="Nueva contraseña" />
      <button>Continuar</button>
    </form>
    html;
  } catch (Throwable) {
    exit('RESPUESTA SECRETA INCORRECTA');
  }
});

Flight::route('POST /restablecer-clave/3', function (): void {
  $nuevaClave = Flight::request()->data->nueva_clave;
  $usuario = Usuario::query()->find(flash()->display('usuarios.id'));

  $usuario->restablecerClave($nuevaClave);
  Flight::redirect('/ingresar');
});

Flight::route('GET /', function (): void {});

Flight::route('GET /perfil', function (): void {});

Flight::route('GET /perfil/editar', function (): void {});

Flight::route('POST /perfil/editar', function (): void {});

Flight::route('GET /empleados', function (): void {});

Flight::route('POST /empleados/@id:\d/restablecer-clave', function (): void {});

Flight::route('/empleados/despedir', function (): void {});

Flight::route('/empleados/promover', function (): void {});

Flight::route('GET /eventos', function (): void {});

////////////////////
// RUTAS PRIVADAS //
////////////////////
// Flight::group(
//   '/@rutaPrivada:(panel|clientes|perfil|productos)/',
//   static fn() => Flight::route('*', 'renderizarSvelte'),
//   [SoloAutenticados::class]
// );

////////////////////
// RUTAS PÚBLICAS //
////////////////////
// Flight::route('POST /ingresar', [ControladorDeSesion::class, 'procesarIngreso']);
// Flight::route('/salir', [ControladorDeSesion::class, 'cerrarSesion']);
// Flight::route('*', 'renderizarSvelte')->addMiddleware(SoloVisitantes::class);
