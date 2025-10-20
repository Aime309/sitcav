<?php

use Illuminate\Container\Container;
use Leaf\Helpers\Password;
use SITCAV\Autorizadores\SoloAutenticados;
use SITCAV\Autorizadores\SoloTasaActualizada;
use SITCAV\Autorizadores\SoloVisitantes;
use SITCAV\Modelos\Cliente;
use SITCAV\Modelos\Producto;
use SITCAV\Modelos\Usuario;
use SITCAV\Modelos\UsuarioAutenticado;

Flight::group('', static function (): void {
  Flight::route('GET /oauth2/google', static function (): void {
    $error = Flight::request()->query->error;
    $codigo = Flight::request()->query->code;
    $estado = Flight::request()->query->state;

    if ($error) {
      flash()->set(["No se pudo iniciar sesión con Google. $error"], 'errores');
      Flight::redirect('/ingresar');

      return;
    }

    if (!$codigo) {
      $urlDeGoogle = auth()->client('google')->getAuthorizationUrl();
      $estadoDeGoogle = auth()->client('google')->getState();
      session()->set('oauth2state', $estadoDeGoogle);
      Flight::redirect($urlDeGoogle);

      return;
    }

    if (!$estado || ($estado !== session()->get('oauth2state'))) {
      session()->remove('oauth2state');
      flash()->set(['No se pudo iniciar sesión con Google. El estado es inválido'], 'errores');
      Flight::redirect('/ingresar');

      return;
    }

    try {
      $token = auth()->client('google')->getAccessToken('authorization_code', [
        'code' => $codigo,
      ]);

      $usuarioDeGoogle = auth()->client('google')->getResourceOwner($token);

      auth()->fromOAuth([
        'token' => $token,
        'user' => [
          'roles' => json_encode(['Encargado', 'Empleado superior', 'Vendedor']),
          'email' => $usuarioDeGoogle->toArray()['email'],
        ],
      ]);

      Flight::redirect('/');
    } catch (Throwable $error) {
      flash()->set(["No se pudo iniciar sesión con Google. {$error->getMessage()}"], 'errores');
      Flight::redirect('/ingresar');

      return;
    }
  });

  Flight::route('GET /ingresar', static function (): void {
    Flight::render('paginas/ingresar', [], 'pagina');
    Flight::render('diseños/diseño-con-alpine-para-visitantes', ['titulo' => 'Ingresar']);
  });

  Flight::route('POST /ingresar', static function (): void {
    $credenciales = Flight::request()->data;

    if (auth()->login([
      'cedula' => $credenciales->cedula,
      'clave_encriptada' => $credenciales->clave,
    ])) {
      Flight::redirect('/');
    } else {
      flash()->set(auth()->errors(), 'errores');
      Flight::redirect('/ingresar');
    }
  });

  Flight::route('GET /registrarse', static function (): void {
    Flight::render('paginas/registrarse', [], 'pagina');
    Flight::render('diseños/diseño-con-alpine-para-visitantes', ['titulo' => 'Registrarse']);
  });

  Flight::route('POST /registrarse', static function (): void {
    $datos = Flight::request()->data;

    if (auth()->register([
      'cedula' => $datos->cedula ?: null,
      'clave_encriptada' => $datos->clave,
      'pregunta_secreta' => $datos->pregunta_secreta ?: null,
      'respuesta_secreta_encriptada' => Password::hash($datos->respuesta_secreta, options: [
        'cost' => 10,
      ]),
      'rol' => json_encode(['Encargado', 'Empleado superior', 'Vendedor']),
    ])) {
      flash()->set(['El registro se ha realizado correctamente.'], 'exitos');
      Flight::redirect('/');
    } else {
      flash()->set(auth()->errors(), 'errores');
      Flight::redirect('/registrarse');
    }
  });

  Flight::route('GET /restablecer-clave', static function (): void {
    Flight::render('paginas/restablecer-clave/paso-1', [], 'pagina');
    Flight::render('diseños/diseño-con-alpine-para-visitantes', ['titulo' => 'Restablecer contraseña']);
  });

  Flight::route('POST /restablecer-clave', static function (): void {
    $cedula = Flight::request()->data->cedula;
    $usuario = Usuario::query()->where('cedula', $cedula)->first();

    if (!$usuario) {
      $intentos = session()->get('restablecer-clave.intentos') ?? 0;
      ++$intentos;

      if ($intentos >= 3) {
        session()->remove('restablecer-clave.intentos');
        flash()->set(['Has alcanzado el número máximo de intentos. Por favor, inténtalo más tarde.'], 'errores');
        Flight::redirect('/ingresar');

        return;
      }

      session()->set('restablecer-clave.intentos', $intentos);
      flash()->set(["Llevas $intentos intentos."], 'errores');
      Flight::render('paginas/restablecer-clave/paso-1', [], 'pagina');
      Flight::render('diseños/diseño-con-alpine-para-visitantes', ['titulo' => 'Restablecer contraseña']);

      return;
    }

    session()->set('usuarios.id', $usuario->id);
    Flight::render('paginas/restablecer-clave/paso-2', ['usuario' => $usuario], 'pagina');
    Flight::render('diseños/diseño-con-alpine-para-visitantes', ['titulo' => 'Restablecer contraseña']);
  });

  Flight::route('POST /restablecer-clave/2', static function (): void {
    $respuestaSecreta = Flight::request()->data->respuesta_secreta;
    $usuario = Usuario::query()->find(session()->get('usuarios.id'));

    if (!$respuestaSecreta) {
      Flight::render('paginas/restablecer-clave/paso-2', ['usuario' => $usuario], 'pagina');
      Flight::render('diseños/diseño-con-alpine-para-visitantes', ['titulo' => 'Restablecer contraseña']);

      return;
    }

    try {
      $usuario->asegurarValidezRespuestaSecreta($respuestaSecreta);
      Flight::render('paginas/restablecer-clave/paso-3', ['usuario' => $usuario], 'pagina');
      Flight::render('diseños/diseño-con-alpine-para-visitantes', ['titulo' => 'Restablecer contraseña']);
    } catch (Throwable) {
      flash()->set(['La respuesta secreta es incorrecta.'], 'errores');
      Flight::render('paginas/restablecer-clave/paso-2', ['usuario' => $usuario], 'pagina');
      Flight::render('diseños/diseño-con-alpine-para-visitantes', ['titulo' => 'Restablecer contraseña']);
    }
  });

  Flight::route('POST /restablecer-clave/3', static function (): void {
    $nuevaClave = Flight::request()->data->nueva_clave;
    $usuario = Usuario::query()->find(session()->get('usuarios.id'));

    $usuario->restablecerClave($nuevaClave);

    auth()->login([
      'cedula' => $usuario->cedula,
      'clave_encriptada' => $nuevaClave,
    ]);

    session()->remove('usuarios.id');
    flash()->set(['La contraseña se ha restablecido correctamente.'], 'exitos');
    Flight::redirect('/');
  });
}, [SoloVisitantes::class]);

Flight::route('/salir', static function (): void {
  auth()->logout();
  session()->remove('oauth-token');
  session()->remove('oauth2state');
  Flight::redirect('/ingresar');
});

Flight::group('', static function (): void {
  Flight::route('POST /cotizaciones', static function (): void {
    $usuarioAutenticado = Container::getInstance()->get(UsuarioAutenticado::class);
    $nuevaTasa = Flight::request()->data->nueva_tasa;

    $usuarioAutenticado->cotizaciones()->create([
      'tasa_bcv' => $nuevaTasa,
    ]);

    flash()->set("Tasa BCV establecida satisfactoriamente en Bs. $nuevaTasa", 'exitos');
    Flight::redirect(Flight::request()->referrer);
  });

  Flight::group('', static function (): void {
    Flight::route('GET /', static function (): void {
      Flight::render('paginas/inicio', [], 'pagina');
      Flight::render('diseños/diseño-con-alpine-para-autenticados', ['titulo' => 'Inicio']);
    });

    Flight::group('/inventario', static function (): void {
      Flight::route('GET /', static function (): void {
        $productos = Producto::query()->get();

        Flight::render('paginas/inventario', ['productos' => $productos], 'pagina');
        Flight::render('diseños/diseño-con-alpine-para-autenticados', ['titulo' => 'Inventario']);
      });

      Flight::route('POST /', static function (): void {
        $datos = Flight::request()->data;

        Producto::query()->create([
          'codigo' => $datos->codigo,
          'nombre' => $datos->nombre,
          'descripcion' => $datos->descripcion,
          'url_imagen' => $datos->url_imagen,
          'precio_unitario_actual_dolares' => $datos->precio_dolares,
          'precio_unitario_actual_bcv' => $datos->precio_bcv,
          'cantidad_disponible' => $datos->cantidad_disponible,
          'dias_garantia' => $datos->dias_garantia,
          'dias_apartado' => $datos->dias_apartado,
          'id_categoria' => $datos->id_categoria,
          'id_marca' => $datos->id_marca,
          'id_proveedor' => $datos->id_proveedor,
        ]);

        flash()->set(['Producto agregado exitosamente.'], 'exitos');
        Flight::redirect('/inventario');
      });

      Flight::route('POST /', static function (): void {});

      Flight::group('/@id:[0-9]+', static function (): void {
        Flight::route('GET /', static function (int $id): void {});

        Flight::route('GET /editar', static function (int $id): void {});

        Flight::route('POST /editar', static function (int $id): void {});
      });
    });

    Flight::route('GET /perfil', static function (): void {});
    Flight::route('GET /perfil/editar', static function (): void {});
    Flight::route('POST /perfil/editar', static function (): void {});

    // Flight::route('GET /empleados', function (): void {});
    // Flight::route('POST /empleados/@id:\d/restablecer-clave', function (): void {});
    // Flight::route('/empleados/despedir', function (): void {});
    // Flight::route('/empleados/promover', function (): void {});

    // Flight::route('GET /eventos', function (): void {});

    Flight::route('GET /vender', static function (): void {
      $productos = Producto::all();
      $clientes = Cliente::all();
      $hrefBase = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
      $productosJson = json_encode($productos);

      echo <<<html
      <base href="$hrefBase" />
      <script src="https://unpkg.com/alpinejs" defer></script>
      <form
        method="post"
        action="./ventas"
        x-data='{
          productos: JSON.parse(`$productosJson`),
          productoSeleccionado: {
            id: undefined,
          },
        }'>
        <select name="cedula_cliente">
          <option value="">Cédula cliente</option>
      html;

      foreach ($clientes as $cliente) {
        echo <<<html
        <option value="$cliente->cedula">$cliente->nombres $cliente->apellidos ($cliente->cedula)</option>
        html;
      }

      echo <<<html
      </select>
      <select name="id_producto" x-model="productoSeleccionado.id">
        <option value="">Productos</option>
      html;

      foreach ($productos as $producto) {
        echo <<<html
        <option value="$producto->id">
          $producto->nombre
        </option>
        html;
      }

      echo <<<html
      </select>
      <template x-show="productoSeleccionado.id">
        <div x-data="{

        }">
          <p x-text="productos.find(p => p.id == producto.id).descripcion"></p>
          <p>
            Precio (Dólares):
            <output x-text="productos.find(p => p.id == producto.id).precio_unitario_actual_dolares"></output>
          </p>
          <p>
            Precio (Bolívares):
            <output x-text="productos.find(p => p.id == producto.id).precio_unitario_actual_bcv"></output>
          </p>
          <p>
            Cantidad disponible:
            <output x-text="productos.find(p => p.id == producto.id).cantidad_disponible"></output>
          </p>
          <label>
            Cantidad a vender
            <input
              type="number"
              name="cantidad"
              min="1"
              :max="productos.find(p => p.id == producto.id).cantidad_disponible"
              required
              placeholder="Cantidad a vender" />
          </label>
        </div>
      </template>
      html;

      echo <<<html
      </form>
      html;
    });
  }, [SoloTasaActualizada::class]);
}, [SoloAutenticados::class]);
