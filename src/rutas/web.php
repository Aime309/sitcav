<?php

use Illuminate\Container\Container;
use Leaf\Helpers\Password;
use PHPMailer\PHPMailer\PHPMailer;
use SITCAV\Autorizadores\SoloAutenticados;
use SITCAV\Autorizadores\SoloContratados;
use SITCAV\Autorizadores\SoloPersonalAutorizado;
use SITCAV\Autorizadores\SoloTasaActualizada;
use SITCAV\Autorizadores\SoloVisitantes;
use SITCAV\Enums\ClaveSesion;
use SITCAV\Enums\Permiso;
use SITCAV\Enums\Rol;
use SITCAV\Modelos\Cliente;
use SITCAV\Modelos\Producto;
use SITCAV\Modelos\Usuario;
use SITCAV\Modelos\UsuarioAutenticado;

Flight::group('', static function (): void {
  Flight::route('GET /oauth2/google', static function (): void {
    $query = Flight::request()->query;
    $error = $query->error;
    $codigo = $query->code;
    $estado = $query->state;

    if ($error) {
      flash()->set(['No se pudo iniciar sesión con Google'], ClaveSesion::MENSAJES_ERRORES->name);
      error_log("Error de OAuth2 de Google: $error");
      Flight::redirect('/ingresar');

      return;
    }

    if (!$codigo) {
      $urlDeGoogle = auth()->client('google')->getAuthorizationUrl();
      $estadoDeGoogle = auth()->client('google')->getState();
      session()->set(ClaveSesion::OAUTH2_ESTADO->name, $estadoDeGoogle);
      Flight::redirect($urlDeGoogle);

      return;
    }

    if (!$estado || ($estado !== session()->get(ClaveSesion::OAUTH2_ESTADO->name))) {
      session()->remove(ClaveSesion::OAUTH2_ESTADO->name);
      flash()->set(['No se pudo iniciar sesión con Google. El estado es inválido'], ClaveSesion::MENSAJES_ERRORES->name);
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
          'roles' => json_encode([
            Rol::ENCARGADO->value,
            Rol::EMPLEADO_SUPERIOR->value,
            Rol::VENDEDOR->value
          ]),
          'email' => $usuarioDeGoogle->toArray()['email'],
          'url_imagen' => $usuarioDeGoogle->toArray()['picture'],
        ],
      ]);

      Flight::redirect('/');
    } catch (Throwable $error) {
      flash()->set(["No se pudo iniciar sesión con Google."], ClaveSesion::MENSAJES_ERRORES->name);
      error_log("Error al autenticar con OAuth2 de Google: {$error->getMessage()}");
      Flight::redirect('/ingresar');

      return;
    }
  });

  Flight::route('GET /ingresar', static function (): void {
    Flight::render('paginas/ingresar', [], 'pagina');
    Flight::render('diseños/materialm-para-visitantes', ['titulo' => 'Ingresar']);
  });

  Flight::route('POST /ingresar', static function (): void {
    $credenciales = Flight::request()->data;

    if (auth()->login([
      'cedula' => $credenciales->cedula,
      'clave_encriptada' => $credenciales->clave,
    ])) {
      Flight::redirect('/');
    } else {
      flash()->set(auth()->errors(), ClaveSesion::MENSAJES_ERRORES->name);
      Flight::redirect('/ingresar');
    }
  });

  Flight::route('GET /registrarse', static function (): void {
    Flight::render('paginas/registrarse', [], 'pagina');
    Flight::render('diseños/materialm-para-visitantes', ['titulo' => 'Registrarse']);
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
      'roles' => json_encode(['Encargado', 'Empleado superior', 'Vendedor']),
    ])) {
      flash()->set(['El registro se ha realizado correctamente.'], ClaveSesion::MENSAJES_EXITOS->name);
      Flight::redirect('/');

      return;
    }

    flash()->set(auth()->errors(), ClaveSesion::MENSAJES_ERRORES->name);
    Flight::redirect('/registrarse');
  });

  Flight::group('/restablecer-clave', static function (): void {
    Flight::route('GET /', static function (): void {
      Flight::render('paginas/restablecer-clave/paso-1', [], 'pagina');
      Flight::render('diseños/materialm-para-visitantes', ['titulo' => 'Restablecer contraseña']);
    });

    Flight::route('POST /', static function (): void {
      $cedula = Flight::request()->data->cedula;
      $usuario = Usuario::query()->where('cedula', $cedula)->first();

      if (!$usuario) {
        flash()->set(['No existe ningún usuario con esa cédula.'], ClaveSesion::MENSAJES_ERRORES->name);
        Flight::render('paginas/restablecer-clave/paso-1', [], 'pagina');
        Flight::render('diseños/materialm-para-visitantes', ['titulo' => 'Restablecer contraseña']);

        return;
      }

      session()->set(ClaveSesion::USUARIO_ID->name, $usuario->id);
      Flight::render('paginas/restablecer-clave/paso-2', compact('usuario'), 'pagina');
      Flight::render('diseños/materialm-para-visitantes', ['titulo' => 'Restablecer contraseña']);
    });

    Flight::route('POST /2', static function (): void {
      $respuestaSecreta = Flight::request()->data->respuesta_secreta;
      $usuario = Usuario::query()->find(session()->get(ClaveSesion::USUARIO_ID->name));

      if (!$respuestaSecreta) {
        Flight::render('paginas/restablecer-clave/paso-2', compact('usuario'), 'pagina');
        Flight::render('diseños/materialm-para-visitantes', ['titulo' => 'Restablecer contraseña']);

        return;
      }

      try {
        $usuario->asegurarValidezRespuestaSecreta($respuestaSecreta);
        Flight::render('paginas/restablecer-clave/paso-3', compact('usuario'), 'pagina');
        Flight::render('diseños/materialm-para-visitantes', ['titulo' => 'Restablecer contraseña']);
      } catch (Throwable) {
        flash()->set(['La respuesta secreta es incorrecta.'], ClaveSesion::MENSAJES_ERRORES->name);
        Flight::render('paginas/restablecer-clave/paso-2', compact('usuario'), 'pagina');
        Flight::render('diseños/materialm-para-visitantes', ['titulo' => 'Restablecer contraseña']);
      }
    });

    Flight::route('POST /3', static function (): void {
      $nuevaClave = Flight::request()->data->nueva_clave;
      $usuario = Usuario::query()->find(session()->get(ClaveSesion::USUARIO_ID->name));

      try {
        $usuario->restablecerClave($nuevaClave);
      } catch (Error $error) {
        flash()->set([$error->getMessage()], ClaveSesion::MENSAJES_ERRORES->name);
        Flight::render('paginas/restablecer-clave/paso-3', compact('usuario'), 'pagina');
        Flight::render('diseños/materialm-para-visitantes', ['titulo' => 'Restablecer contraseña']);

        return;
      }

      if (session()->has(ClaveSesion::USUARIO_CORREO->name)) {
        auth()->login([
          'email' => session()->get(ClaveSesion::USUARIO_CORREO->name),
          'clave_encriptada' => $nuevaClave,
        ]);
      } else {
        auth()->login([
          'cedula' => $usuario->cedula,
          'clave_encriptada' => $nuevaClave,
        ]);
      }

      session()->remove(ClaveSesion::USUARIO_ID->name);
      session()->remove(ClaveSesion::USUARIO_CORREO->name);
      flash()->set(['La contraseña se ha restablecido correctamente.'], ClaveSesion::MENSAJES_EXITOS->name);
      Flight::redirect('/');
    });

    Flight::route('POST /solicitar-codigo', static function (): void {
      $correo = session()->retrieve(ClaveSesion::USUARIO_CORREO->name, Flight::request()->data->correo);
      $usuario = Usuario::query()->where('email', $correo)->first();

      if (!$usuario) {
        flash()->set(['No existe ningún usuario con ese correo.'], ClaveSesion::MENSAJES_ERRORES->name);
        Flight::redirect('/restablecer-clave');

        return;
      }

      $clienteCorreo = new PHPMailer;
      $codigoVerificacion = rand(100000, 999999);

      session()->set(ClaveSesion::CODIGO_VERIFICACION->name, $codigoVerificacion);
      session()->set(ClaveSesion::CODIGO_VERIFICACION_EXPIRACION->name, time() + 60); // 1 minuto
      session()->set(ClaveSesion::USUARIO_CORREO->name, $usuario->email);
      session()->set(ClaveSesion::USUARIO_ID->name, $usuario->id);

      try {
        $clienteCorreo->isSMTP();
        $clienteCorreo->Host = $_ENV['PHPMAILER_HOST'];
        $clienteCorreo->SMTPAuth = true;
        $clienteCorreo->Username = $_ENV['PHPMAILER_USERNAME'];
        $clienteCorreo->Password = $_ENV['PHPMAILER_PASSWORD'];
        $clienteCorreo->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $clienteCorreo->Port = 465;
        $clienteCorreo->setFrom($_ENV['PHPMAILER_USERNAME'], 'SITCAV');
        $clienteCorreo->addAddress($correo);
        $clienteCorreo->isHTML(true);
        $clienteCorreo->CharSet = 'UTF-8';
        $clienteCorreo->Subject = 'Tu código de verificación';
        $clienteCorreo->Body = Flight::view()->fetch('paginas/correo', ['codigo' => $codigoVerificacion]);
        $clienteCorreo->send();
      } catch (Exception) {
        flash()->set(['No se pudo enviar el correo con el código de verificación. Por favor, intenta nuevamente más tarde.'], ClaveSesion::MENSAJES_ERRORES->name);
        error_log("Error al enviar correo: {$clienteCorreo->ErrorInfo}");
        Flight::redirect('/restablecer-clave');

        return;
      }

      Flight::render(
        'paginas/restablecer-clave/codigo-verificacion',
        [],
        'pagina'
      );

      Flight::render(
        'diseños/materialm-para-visitantes',
        ['titulo' => 'Restablecer contraseña']
      );
    });

    Flight::route('POST /verificar-codigo', static function (): void {
      $codigoIngresado = implode('', Flight::request()->data->codigo);
      $codigoAlmacenado = session()->get(ClaveSesion::CODIGO_VERIFICACION->name);
      $expiracionCodigo = session()->get(ClaveSesion::CODIGO_VERIFICACION_EXPIRACION->name);

      if (time() > $expiracionCodigo) {
        flash()->set(['El código de verificación ha expirado. Por favor, solicita uno nuevo.'], ClaveSesion::MENSAJES_ERRORES->name);
        Flight::redirect('/restablecer-clave');

        return;
      }

      if ($codigoIngresado !== (string) $codigoAlmacenado) {
        flash()->set(['El código de verificación es incorrecto.'], ClaveSesion::MENSAJES_ERRORES->name);

        Flight::render(
          'paginas/restablecer-clave/codigo-verificacion',
          [],
          'pagina'
        );

        Flight::render(
          'diseños/materialm-para-visitantes',
          ['titulo' => 'Restablecer contraseña']
        );

        return;
      }

      session()->remove(ClaveSesion::CODIGO_VERIFICACION->name);
      session()->remove(ClaveSesion::CODIGO_VERIFICACION_EXPIRACION->name);

      Flight::render(
        'paginas/restablecer-clave/paso-3',
        [],
        'pagina'
      );

      Flight::render(
        'diseños/materialm-para-visitantes',
        ['titulo' => 'Restablecer contraseña']
      );
    });
  });
}, [SoloVisitantes::class]);

Flight::route('/salir', static function (): void {
  auth()->logout();
  session()->remove(ClaveSesion::OAUTH2_TOKEN->name);
  session()->remove(ClaveSesion::OAUTH2_ESTADO->name);

  flash()->set(
    session()->retrieve(ClaveSesion::MENSAJES_ERRORES->name, []),
    ClaveSesion::MENSAJES_ERRORES->name
  );

  Flight::redirect('/ingresar');
});

Flight::group('', static function (): void {
  Flight::route('POST /cotizaciones', static function (): void {
    $usuarioAutenticado = Container::getInstance()->get(UsuarioAutenticado::class);
    $nuevaTasa = Flight::request()->data->nueva_tasa;
    $usuarioAutenticado->cotizaciones()->create(['tasa_bcv' => $nuevaTasa]);

    flash()->set("Tasa BCV establecida satisfactoriamente en Bs. $nuevaTasa", ClaveSesion::MENSAJES_EXITOS->name);
    Flight::redirect(Flight::request()->referrer);
  });

  Flight::group('', static function (): void {
    Flight::route('GET /', static function (): void {
      Flight::render('paginas/inicio', [], 'pagina');
      Flight::render('diseños/materialm-para-autenticados', ['titulo' => 'Inicio']);
    });

    Flight::group('/inventario', static function (): void {
      Flight::route('GET /', static function (): void {
        $usuarioAutenticado = Container::getInstance()->get(UsuarioAutenticado::class);
        $productos = $usuarioAutenticado->productos;
        $categorias = $usuarioAutenticado->categorias;
        $marcas = $usuarioAutenticado->marcas;
        $proveedores = $usuarioAutenticado->proveedores;

        Flight::render(
          'paginas/inventario',
          compact('productos', 'categorias', 'proveedores', 'marcas'),
          'pagina'
        );
        Flight::render('diseños/materialm-para-autenticados', ['titulo' => 'Inventario']);
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

        flash()->set(['Producto agregado exitosamente.'], ClaveSesion::MENSAJES_EXITOS->name);
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

    Flight::group('/empleados', static function (): void {
      Flight::route('GET /', static function (): void {
        $empleados = Container::getInstance()->get(UsuarioAutenticado::class)->empleados;

        Flight::render('paginas/empleados', compact('empleados'), 'pagina');
        Flight::render('diseños/materialm-para-autenticados', ['titulo' => 'Empleados']);
      })->addMiddleware(new SoloPersonalAutorizado(Permiso::VER_EMPLEADOS));

      Flight::route('POST /restablecer-clave/@id', static function (int $id): void {
        $nuevaClave = Flight::request()->data->nueva_clave;
        $empleado = Usuario::query()->find($id);

        try {
          $empleado->restablecerClave($nuevaClave);
          flash()->set(['La contraseña se ha restablecido correctamente.'], ClaveSesion::MENSAJES_EXITOS->name);
        } catch (Error $error) {
          flash()->set([$error->getMessage()], ClaveSesion::MENSAJES_ERRORES->name);
        }

        Flight::redirect('/empleados');
      })->addMiddleware(new SoloPersonalAutorizado(Permiso::RESTABLECER_CLAVE_EMPLEADO));

      Flight::route('/despedir/@id', static function (int $id): void {
        $empleados = Container::getInstance()->get(UsuarioAutenticado::class)->empleados;

        $empleado = $empleados->find($id);

        if (!$empleado instanceof Usuario) {
          flash()->set(['El empleado no existe o no te pertenece.'], ClaveSesion::MENSAJES_ERRORES->name);
          Flight::redirect('/empleados');

          return;
        }

        $empleado->esta_despedido = true;
        $empleado->save();
        flash()->set(['El empleado ha sido despedido exitosamente.'], ClaveSesion::MENSAJES_EXITOS->name);
        Flight::redirect('/empleados');
      })->addMiddleware(new SoloPersonalAutorizado(Permiso::DESPEDIR_EMPLEADO));

      Flight::route('/recontratar/@id', static function (int $id): void {
        $empleados = Container::getInstance()->get(UsuarioAutenticado::class)->empleados;
        $empleado = $empleados->find($id);

        if (!$empleado instanceof Usuario) {
          flash()->set(['El empleado no existe o no te pertenece.'], ClaveSesion::MENSAJES_ERRORES->name);
          Flight::redirect('/empleados');

          return;
        }

        $empleado->esta_despedido = false;
        $empleado->save();
        flash()->set(['El empleado ha sido recontratado exitosamente.'], ClaveSesion::MENSAJES_EXITOS->name);
        Flight::redirect('/empleados');
      })->addMiddleware(new SoloPersonalAutorizado(Permiso::RECONTRATAR_EMPLEADO));

      Flight::route('/promover/@id', static function (int $id): void {
        $empleados = Container::getInstance()->get(UsuarioAutenticado::class)->empleados;
        $empleado = $empleados->find($id);

        if (!$empleado instanceof Usuario) {
          flash()->set(['El empleado no existe o no te pertenece.'], ClaveSesion::MENSAJES_ERRORES->name);
          Flight::redirect('/empleados');

          return;
        }

        if (in_array('Empleado superior', $empleado->roles)) {
          flash()->set(['El empleado ya es un Empleado superior.'], ClaveSesion::MENSAJES_ERRORES->name);
          Flight::redirect('/empleados');

          return;
        }

        try {
          db()->update('usuarios')->params([
            'roles' => '["Empleado superior", "Vendedor"]',
          ])->where('id', $empleado->id)->execute();
          flash()->set(['El empleado ha sido promovido a Empleado superior exitosamente.'], ClaveSesion::MENSAJES_EXITOS->name);
        } catch (Throwable $error) {
          flash()->set(['No se pudo promover al empleado. Por favor, intenta nuevamente más tarde.'], ClaveSesion::MENSAJES_ERRORES->name);
          error_log("Error al promover empleado (ID: $id): {$error->getMessage()}");
        }

        Flight::redirect('/empleados');
      })->addMiddleware(new SoloPersonalAutorizado(Permiso::PROMOVER_VENDEDOR));

      Flight::route('/degradar/@id', static function (int $id): void {
        $empleados = Container::getInstance()->get(UsuarioAutenticado::class)->empleados;
        $empleado = $empleados->find($id);

        if (!$empleado instanceof Usuario) {
          flash()->set(['El empleado no existe o no te pertenece.'], ClaveSesion::MENSAJES_ERRORES->name);
          Flight::redirect('/empleados');

          return;
        }

        if (!in_array('Empleado superior', $empleado->roles)) {
          flash()->set(['El empleado ya es un Vendedor.'], ClaveSesion::MENSAJES_ERRORES->name);
          Flight::redirect('/empleados');

          return;
        }

        try {
          db()->update('usuarios')->params([
            'roles' => '["Vendedor"]',
          ])->where('id', $empleado->id)->execute();

          flash()->set(['El empleado ha sido degradado a Vendedor exitosamente.'], ClaveSesion::MENSAJES_EXITOS->name);
        } catch (Throwable $error) {
          flash()->set(['No se pudo degradar al empleado. Por favor, intenta nuevamente más tarde.'], ClaveSesion::MENSAJES_ERRORES->name);
          error_log("Error al degradar empleado (ID: $id): {$error->getMessage()}");
        }

        Flight::redirect('/empleados');
      })->addMiddleware(new SoloPersonalAutorizado(Permiso::DEGRADAR_EMPLEADO_SUPERIOR));

      Flight::route('GET /registrar', static function (): void {
        Flight::render('paginas/empleados/registrar', [], 'pagina');
        Flight::render('diseños/materialm-para-autenticados', ['titulo' => 'Registrar empleado']);
      })->addMiddleware(new SoloPersonalAutorizado(Permiso::CONTRATAR_EMPLEADO));

      Flight::route('POST /', static function (): void {
        $datos = Flight::request()->data;
        $roles = [Rol::VENDEDOR->value];

        if ($datos->rol === Rol::EMPLEADO_SUPERIOR->value) {
          $roles[] = Rol::EMPLEADO_SUPERIOR->value;
        }

        if (auth()->createUserFor([
          'email' => $datos->correo ?: null,
          'cedula' => $datos->cedula ?: null,
          'clave_encriptada' => $datos->clave,
          'roles' => json_encode($roles),
          'url_imagen' => $datos->url_imagen ?: null,
          'id_encargado' => auth()->id(),
        ])) {
          flash()->set(['Empleado contratado correctamente.'], ClaveSesion::MENSAJES_EXITOS->name);
          Flight::redirect('/empleados');

          return;
        }

        flash()->set(auth()->errors(), ClaveSesion::MENSAJES_ERRORES->name);
        Flight::redirect('/empleados/registrar');
      })->addMiddleware(new SoloPersonalAutorizado(Permiso::CONTRATAR_EMPLEADO));
    });

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
}, [SoloAutenticados::class, SoloContratados::class]);
